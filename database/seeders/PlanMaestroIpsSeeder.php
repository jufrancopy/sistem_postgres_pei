<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PlanMaestro\PlanMaestro;
use App\Models\PlanMaestro\PlanEje;
use App\Models\PlanMaestro\PlanAccion;

class PlanMaestroIpsSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar datos previos si existen
        DB::statement('TRUNCATE TABLE plan_acciones CASCADE;');
        DB::statement('TRUNCATE TABLE plan_ejes CASCADE;');
        DB::statement('TRUNCATE TABLE plan_maestros CASCADE;');

        $plan = PlanMaestro::create([
            'nombre'      => 'Plan de Gestión 2026',
            'institucion' => 'Instituto de Previsión Social — República del Paraguay',
            'descripcion' => 'Primeros 100 días + Hoja de Ruta 9 meses (Prof. Dr. Isaías R. Fretes Zárate)',
            'responsable' => 'Presidencia del IPS',
            'periodo'     => '2026',
            'activo'      => true,
        ]);

        // ── 9 Ejes Estratégicos del Plan de Gestión ────────────────────────────
        $ejes = [
            ['A', 'Gobernanza',    '#2a9d8f', 'fa-landmark'],
            ['B', 'Vademécum',     '#e9c46a', 'fa-pills'],
            ['C', 'Contratos',     '#f4a261', 'fa-file-contract'],
            ['D', 'Prestaciones',  '#e76f51', 'fa-hospital'],
            ['E', 'Finanzas',      '#264653', 'fa-chart-line'],
            ['F', 'Inmobiliario',  '#8ab17d', 'fa-building'],
            ['G', 'Jubilaciones',  '#bc6c25', 'fa-piggy-bank'],
            ['H', 'Rec. Humanos',  '#d62828', 'fa-users'],
            ['I', 'Comunicación',  '#7209b7', 'fa-bullhorn'],
        ];

        $ejeMap = [];
        foreach ($ejes as $i => [$codigo, $nombre, $color, $icono]) {
            $eje = PlanEje::create([
                'plan_id' => $plan->id,
                'codigo'  => $codigo,
                'nombre'  => $nombre,
                'color'   => $color,
                'icono'   => $icono,
                'orden'   => $i,
            ]);
            $ejeMap[$codigo] = $eje->id;
        }

        // Mapeo por defecto de Ejes a UUIDs de Acciones PEI en Producción
        $peiEjeMap = [
            'A' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'B' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',
            'C' => 'caf00eb8-6960-4deb-b7b9-0ab91dfdda49',
            'D' => 'dec95d29-07dd-40c1-9628-987820299120',
            'E' => '31218f42-059c-4804-9712-5c3812ced212',
            'F' => '0c05cd2a-9d57-40e2-9947-d791fda9acea',
            'G' => '29587945-7e1c-4009-b22a-e1d8707c4530',
            'H' => 'e48761a8-0b05-4362-97df-2c0fbaa87e46',
            'I' => '3e834b0f-97b2-41f2-a628-8526188690fb',
        ];

        // Mapeo específico y granular por código de Iniciativa a Nodos Acción PEI en Producción
        $specificCodeMap = [
            // EJE A · Gobernanza
            'A-01' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-02' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-03' => 'c62263ee-c882-4050-b6df-77e82c3c4679',
            'A-04' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-05' => 'c62263ee-c882-4050-b6df-77e82c3c4679',
            'A-06' => 'c62263ee-c882-4050-b6df-77e82c3c4679',
            'A-07' => 'a1d81f57-e5a9-46a4-97eb-9bf6933ce4f4',
            'A-08' => 'a1d81f57-e5a9-46a4-97eb-9bf6933ce4f4',
            'A-09' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-10' => 'c62263ee-c882-4050-b6df-77e82c3c4679',
            'A-11' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-12' => 'a27752f7-cb9b-4082-af59-0e18f238f03b',
            'A-13' => 'c62263ee-c882-4050-b6df-77e82c3c4679',
            'A-14' => 'a1d81f57-e5a9-46a4-97eb-9bf6933ce4f4',

            // EJE B · Vademécum
            'B-01' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-02' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-03' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-04' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-05' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-06' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-07' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',
            'B-08' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',
            'B-09' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-10' => '5e152af6-bf03-484e-bce8-d1224e518c72',
            'B-11' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',
            'B-12' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',
            'B-13' => 'd1925b92-d58c-450f-abab-5e9662e72b3f',

            // EJE C · Contratos
            'C-10' => 'b00f0ebf-ed83-489d-b712-264659a6572c',
            'C-11' => 'b00f0ebf-ed83-489d-b712-264659a6572c',
            'C-16' => '1409f5c3-f125-44e2-998b-fc76aae2140a',

            // EJE D · Prestaciones
            'D-01' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',
            'D-02' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',
            'D-03' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',
            'D-04' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-05' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-06' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',
            'D-07' => '3b375a3c-87e8-4bc6-989d-dfc1b16eaf81',
            'D-08' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-09' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-10' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-11' => 'dec95d29-07dd-40c1-9628-987820299120',
            'D-12' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',
            'D-13' => 'd5f46bc4-08b9-4014-bf76-a77f3f6cd73a',

            // EJE E · Finanzas
            'E-01' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-02' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-03' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-04' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-05' => '31218f42-059c-4804-9712-5c3812ced212',
            'E-06' => '31218f42-059c-4804-9712-5c3812ced212',
            'E-07' => '31218f42-059c-4804-9712-5c3812ced212',
            'E-08' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-09' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-10' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-11' => '532a5707-a7be-499d-a5f6-b7485afefbe2',
            'E-12' => '4b54eb48-4c2e-45da-875f-4e7448e0089e',
            'E-13' => '4b54eb48-4c2e-45da-875f-4e7448e0089e',
        ];

        $items = $this->getDatasetAcciones();

        foreach ($items as $i => $item) {
            $ejeLetter   = substr($item['codigo'], 0, 1);
            $targetPeiId = $specificCodeMap[$item['codigo']] ?? ($peiEjeMap[$ejeLetter] ?? null);

            // Si el UUID de producción no existe en la BD local, buscar por coincidencia temática inteligente
            if ($targetPeiId && !\App\Admin\Planificacion\Pei\PeiProfile::where('id', $targetPeiId)->exists()) {
                $activeMaster = \App\Admin\Planificacion\Pei\PeiProfile::whereNull('parent_id')
                    ->where('level', 'master')
                    ->where('type', 'corporative')
                    ->where('is_active', true)
                    ->first();

                $activeActionIds = $activeMaster 
                    ? $activeMaster->descendants()->where('level', 'action')->pluck('id')->toArray()
                    : \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'action')->pluck('id')->toArray();

                if (!empty($activeActionIds)) {
                    $targetPeiId = match($ejeLetter) {
                        'A' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%estratégic%')->orWhere('name', 'ilike', '%mecip%')->orWhere('name', 'ilike', '%procesos%'); })->first()?->id,
                        'B' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%abastec%')->orWhere('name', 'ilike', '%protocol%')->orWhere('name', 'ilike', '%vademécum%'); })->first()?->id,
                        'C' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%pac%')->orWhere('name', 'ilike', '%infraestruct%')->orWhere('name', 'ilike', '%contrat%'); })->first()?->id,
                        'D' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%obstétric%')->orWhere('name', 'ilike', '%redes%')->orWhere('name', 'ilike', '%salud%'); })->first()?->id,
                        'E' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%reserv%')->orWhere('name', 'ilike', '%recaud%')->orWhere('name', 'ilike', '%finanz%'); })->first()?->id,
                        'F' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%inmob%')->orWhere('name', 'ilike', '%patrimon%'); })->first()?->id,
                        'G' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%prestac%')->orWhere('name', 'ilike', '%jubil%'); })->first()?->id,
                        'H' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%concurs%')->orWhere('name', 'ilike', '%human%'); })->first()?->id,
                        'I' => \App\Admin\Planificacion\Pei\PeiProfile::whereIn('id', $activeActionIds)->where(function($q) { $q->where('name', 'ilike', '%informac%')->orWhere('name', 'ilike', '%comunic%'); })->first()?->id,
                        default => null,
                    };

                    // Si no hubo coincidencia de palabras clave, asignar de forma balanceada entre los nodos existentes
                    if (!$targetPeiId) {
                        $index = $i % count($activeActionIds);
                        $targetPeiId = $activeActionIds[$index];
                    }
                }
            }

            // Extraer código de momento (T0, T1, T2, T3, T4, T5, TX)
            $momentoRaw = $item['momento'];
            preg_match('/^(T[0-5X])/', $momentoRaw, $matches);
            $momentoCode = $matches[1] ?? 'TX';

            PlanAccion::create([
                'plan_id'        => $plan->id,
                'eje_id'         => $ejeMap[$ejeLetter],
                'pei_profile_id' => $targetPeiId,
                'codigo'         => $item['codigo'],
                'momento'        => $momentoCode,
                'accion'         => $item['titulo'],
                'justificacion'  => $item['diagnostico_objetivo'] ?? null,
                'detalle'        => $item['detalle_ejecucion'] ?? null,
                'kpi'            => $item['indicador'] ?? null,
                'plazo'          => $item['hito_fecha'] ?? null,
                'responsable'    => $item['responsable'] ?? null,
                'estado'         => strtoupper($item['estado']),
                'orden'          => $i,
            ]);
        }
    }

    private function getDatasetAcciones(): array
    {
        return [
            // EJE A · Gobernanza
            ['codigo' => 'A-01', 'eje' => 'A · Gobernanza', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Pedido formal de cargos a disposición', 'diagnostico_objetivo' => 'Quiebre simbólico con la gestión anterior y habilitación política para la renovación de cargos de confianza.', 'detalle_ejecucion' => 'Solicitado a la totalidad de consejeros, gerentes y directores. Pendiente: insistir con los tres consejeros remanentes (Jara Rojas, Insfrán Dietrich, Argaña Contreras).', 'indicador' => '% de cargos efectivamente puestos a disposición y resueltos', 'hito_fecha' => 'Hito día 1 (22/04/2026)', 'responsable' => 'Presidencia del IPS'],
            ['codigo' => 'A-02', 'eje' => 'A · Gobernanza', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Renovación de asesores de Presidencia', 'diagnostico_objetivo' => 'Conformar un primer anillo técnico alineado con la nueva conducción.', 'detalle_ejecucion' => 'Salieron Torres de Argüello, Rodríguez Álvarez, Vargas Morales, Rolón Ibarra; ingresaron Lovera Cañete, González Parra, Rodríguez Maidana y Martínez Bogado.', 'indicador' => 'Anillo de Presidencia completo y operativo', 'hito_fecha' => 'Resolución del 4 de mayo 2026', 'responsable' => 'Presidencia del IPS'],
            ['codigo' => 'A-03', 'eje' => 'A · Gobernanza', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Renovación de cuatro direcciones clave', 'diagnostico_objetivo' => 'Garantizar control interno robusto, planificación articulada y dirección de calidad.', 'detalle_ejecucion' => 'Gabinete (Néstor Carrillo Rotela), Auditoría Interna (Walter Laguardia Lovera), Planificación (Julio Franco Báez), Organización y Calidad (María Acosta Faranda).', 'indicador' => 'Direcciones operativas con planes de trabajo en 30 días', 'hito_fecha' => 'Resoluciones N° 032-018 a 032-021/2026', 'responsable' => 'Presidencia del IPS'],
            ['codigo' => 'A-05', 'eje' => 'A · Gobernanza', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EN CURSO', 'titulo' => 'Auditoría externa de la Contraloría General de la República', 'diagnostico_objetivo' => 'La CGR ya tenía un informe lapidario (4 de 29 observaciones aprobadas). La auditoría es control y blindaje político.', 'detalle_ejecucion' => 'Sobre situación financiera, presupuestaria y patrimonial del IPS, en el marco del acto de entrega y recepción.', 'indicador' => 'Informe consolidado de la CGR. % de observaciones aceptadas', 'hito_fecha' => 'Cierre ≈ 4 de junio 2026', 'responsable' => 'Auditoría Interna + Presidencia + CGR'],
            ['codigo' => 'A-09', 'eje' => 'A · Gobernanza', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EN CURSO', 'titulo' => 'Sesiones del Consejo transmitidas en vivo', 'diagnostico_objetivo' => 'Pilar de transparencia. Disuade la captura institucional y consolida legitimidad.', 'detalle_ejecucion' => 'Aplicado los días 29/04, 7/05, 10/05 y 12/05.', 'indicador' => 'N° de sesiones transmitidas / realizadas', 'hito_fecha' => 'Permanente', 'responsable' => 'Presidencia + Dirección de Prensa'],
            ['codigo' => 'A-04', 'eje' => 'A · Gobernanza', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Insistencia formal con consejeros remanentes', 'diagnostico_objetivo' => 'La movilización de UNJP exige la renovación del Consejo.', 'detalle_ejecucion' => 'Procedimientos previstos en la Carta Orgánica para Jara Rojas y Argaña Contreras.', 'indicador' => 'N° de procedimientos formales iniciados', 'hito_fecha' => 'Inicio Bloque 1; cierre hacia mes 6', 'responsable' => 'Presidencia + Asesoría Jurídica'],
            ['codigo' => 'A-07', 'eje' => 'A · Gobernanza', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Inventario auditado de pasivos del Fondo de Salud', 'diagnostico_objetivo' => 'Hoja de ruta del flujo financiero futuro; condición previa a cualquier negociación.', 'detalle_ejecucion' => 'Fiducias, cesiones 2024-2025, préstamos, deuda flotante, órdenes no contabilizadas, servicios públicos, tasas municipales.', 'indicador' => 'Documento entregado al cierre del día 30', 'hito_fecha' => '≈ 22 de mayo 2026', 'responsable' => 'Gerencia Adm. y Financiera + Planificación'],
            ['codigo' => 'A-08', 'eje' => 'A · Gobernanza', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Mapeo integral de contratos activos', 'diagnostico_objetivo' => 'Insumo para la racionalización del gasto y cierre de canillas contractuales.', 'detalle_ejecucion' => 'Vencimientos, saldos por ejecutar, contratos regulares, irregulares y pseudo-prioritarios.', 'indicador' => '% de contratos mapeados. N° identificados para suspensión', 'hito_fecha' => 'Cierre día 30', 'responsable' => 'Gerencia de Logística + Asesoría Jurídica'],
            ['codigo' => 'A-06', 'eje' => 'A · Gobernanza', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Auditorías internas puntuales priorizadas', 'diagnostico_objetivo' => 'Convertir denuncias mediáticas en expedientes administrativos con sustento documental.', 'detalle_ejecucion' => 'Sistema antiincendio, prótesis con ejecución cero, bolsas pediátricas, enzalutamida, licitación de ascensores, contratos sobredimensionados.', 'indicador' => 'N° de auditorías con informe final. Monto recuperado', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría Interna (Laguardia Lovera)'],
            ['codigo' => 'A-10', 'eje' => 'A · Gobernanza', 'momento' => 'T3 · Días 61–100', 'estado' => 'EN CURSO', 'titulo' => 'Tablero de Control Institucional', 'diagnostico_objetivo' => 'Sustituye la lógica de informes mensuales por gestión en tiempo casi real.', 'detalle_ejecucion' => 'Stock por farmacia, listas de espera, productividad, ejecución contractual, alertas tempranas, canillas cerradas.', 'indicador' => 'Tablero operativo con 12 indicadores estratégicos', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Planificación + Org. y Calidad + TI'],
            ['codigo' => 'A-11', 'eje' => 'A · Gobernanza', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Apertura del proceso de revisión de la Carta Orgánica', 'diagnostico_objetivo' => 'Modernización institucional requiere base normativa nueva. Vigente desde 1992.', 'detalle_ejecucion' => 'Con representación del Consejo, gremios, academia y Poderes del Estado.', 'indicador' => 'Mesa instalada. Agenda mínima acordada', 'hito_fecha' => 'Días 90–100', 'responsable' => 'Presidencia + Asesoría Jurídica'],
            ['codigo' => 'A-12', 'eje' => 'A · Gobernanza', 'momento' => 'T5 · Meses 7–9', 'estado' => 'PENDIENTE', 'titulo' => 'Anteproyecto de Carta Orgánica reformada', 'diagnostico_objetivo' => 'Materializa el horizonte estructural del plan.', 'detalle_ejecucion' => 'Texto que pueda ser elevado al Congreso.', 'indicador' => 'Anteproyecto entregado al Poder Ejecutivo', 'hito_fecha' => 'Enero 2027', 'responsable' => 'Mesa técnica multisectorial'],
            ['codigo' => 'A-13', 'eje' => 'A · Gobernanza', 'momento' => 'T5 · Meses 7–9', 'estado' => 'PENDIENTE', 'titulo' => 'Cumplimiento del 80% de observaciones de la CGR', 'diagnostico_objetivo' => 'Línea de base: 4 de 29 observaciones aprobadas (14%).', 'detalle_ejecucion' => 'Plan formal de respuesta a las 25 observaciones no resueltas del ejercicio 2024.', 'indicador' => '% de observaciones cumplidas. Meta: 80%', 'hito_fecha' => 'Mes 9', 'responsable' => 'Auditoría Interna + áreas observadas'],
            ['codigo' => 'A-14', 'eje' => 'A · Gobernanza', 'momento' => 'TX · Transversal', 'estado' => 'PENDIENTE', 'titulo' => 'Traslado de denuncias públicas a denuncias formales', 'diagnostico_objetivo' => 'La denuncia mediática sin traducción judicial pierde fuerza con el tiempo.', 'detalle_ejecucion' => 'Bolsas pediátricas, ascensores, antiincendio, enzalutamida, prótesis ante Ministerio Público y Contraloría.', 'indicador' => 'N° de denuncias formales radicadas', 'hito_fecha' => 'Permanente; primera batería en Bloque 2', 'responsable' => 'Asesoría Jurídica + Presidencia'],

            // EJE B · Vademécum
            ['codigo' => 'B-01', 'eje' => 'B · Vademécum', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Taller de revisión del vademécum en Ykua Satí', 'diagnostico_objetivo' => 'Migrar la gobernanza de las compras del área administrativa al área médica.', 'detalle_ejecucion' => 'Con jefes de servicio, directores de hospital y profesionales de blanco.', 'indicador' => 'N° de productos identificados (988 al 16/05)', 'hito_fecha' => '13/05/2026', 'responsable' => 'Gerencia de Salud + jefes médicos'],
            ['codigo' => 'B-02', 'eje' => 'B · Vademécum', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EN CURSO', 'titulo' => 'Anuncio del retiro de 988 ítems del vademécum', 'diagnostico_objetivo' => 'El cambio del vademécum es entrar al corazón de la corrupción.', 'detalle_ejecucion' => 'De 4.000 productos del vademécum. Conferencia con auditores del Poder Ejecutivo.', 'indicador' => 'Cifra final consolidada. Ahorro proyectado', 'hito_fecha' => 'Anunciado 18/05; cifra final en 30–45 días', 'responsable' => 'Presidencia + Gerencia de Salud'],
            ['codigo' => 'B-03', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Resolución formal del nuevo vademécum', 'diagnostico_objetivo' => 'Sin acto administrativo formal, el retiro queda como anuncio.', 'detalle_ejecucion' => 'Establecer el nuevo vademécum institucional depurado.', 'indicador' => 'Resolución publicada. Vademécum vigente con N° final', 'hito_fecha' => 'Días 30–45', 'responsable' => 'Consejo + Gerencia de Salud + Jurídica'],
            ['codigo' => 'B-04', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Definición del Cuadro Básico de Insumos depurado', 'diagnostico_objetivo' => 'El cuadro básico cubre insumos; ambos drenan recursos si no están depurados.', 'detalle_ejecucion' => 'Con criterio técnico-médico, junto a sociedades científicas y academia.', 'indicador' => 'Cuadro Básico vigente con protocolo de revisión anual', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Salud + sociedades científicas'],
            ['codigo' => 'B-05', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Nueva gobernanza de compras (decisión médica, no administrativa)', 'diagnostico_objetivo' => 'Las licitaciones eran definidas por el sector administrativo sin criterios médicos.', 'detalle_ejecucion' => 'Transferir la decisión de qué se compra hacia la Gerencia de Salud.', 'indicador' => 'Resolución vigente. % de licitaciones bajo nueva gobernanza', 'hito_fecha' => 'Días 30–45', 'responsable' => 'Consejo + Gerencia de Salud'],
            ['codigo' => 'B-06', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Programación de compras por consumo histórico real', 'diagnostico_objetivo' => 'Las compras infladas son una de las canillas más caras y silenciosas.', 'detalle_ejecucion' => 'Caso enzalutamida: 15.000 dosis/mes reales vs. 110.000 solicitadas.', 'indicador' => '% de licitaciones con justificación técnica de cantidad', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'B-07', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Cumplimiento del plazo de 45 días para stock cero', 'diagnostico_objetivo' => 'Compromiso público del Dr. Fretes: hito de credibilidad inmediato.', 'detalle_ejecucion' => '150 medicamentos en stock cero. 60 ítems ya adjudicados al 13/05. Incluye trasplantes renales, oncológicos, kits oftalmológicos.', 'indicador' => 'N° de medicamentos en stock cero. Meta: 0 al mes 4', 'hito_fecha' => '≈ 6 de junio 2026', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'B-08', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Sistema de trazabilidad de medicamentos', 'diagnostico_objetivo' => 'La ausencia de trazabilidad habilita fugas a circuitos privados.', 'detalle_ejecucion' => 'Desde recepción en parque sanitario hasta dispensación al paciente. Piloto en 3 hospitales y 20 farmacias.', 'indicador' => 'N° de farmacias con trazabilidad activa', 'hito_fecha' => 'Piloto días 60–100; despliegue mes 4–6', 'responsable' => 'Gerencia de Salud + Logística + TI'],
            ['codigo' => 'B-09', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Convenio CEMIT-UNA para control externo de calidad', 'diagnostico_objetivo' => 'El Laboratorio interno ha sido permisivo según diagnósticos previos.', 'detalle_ejecucion' => 'Auditoría externa cruzada del Laboratorio de Control de Calidad del IPS.', 'indicador' => 'N° de muestras auditadas. % de discrepancias', 'hito_fecha' => 'Días 45–60 para convenio', 'responsable' => 'Gerencia de Salud + CEMIT-UNA'],
            ['codigo' => 'B-10', 'eje' => 'B · Vademécum', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Desactivación de la licitación pendiente de G. 76.000 millones', 'diagnostico_objetivo' => 'Caso paradigmático: licitación sin justificación vs. 164 asegurados en riesgo de ceguera.', 'detalle_ejecucion' => 'Reasignación a prioridades reales como kits oftalmológicos.', 'indicador' => 'Licitación desactivada. Recursos reasignados', 'hito_fecha' => 'Días 30–45', 'responsable' => 'Presidencia + Consejo + Logística'],
            ['codigo' => 'B-11', 'eje' => 'B · Vademécum', 'momento' => 'T3 · Días 61–100', 'estado' => 'EN CURSO', 'titulo' => '95% de disponibilidad del cuadro básico', 'diagnostico_objetivo' => 'Compromiso público del Dr. Fretes. Define el éxito de los 100 días.', 'detalle_ejecucion' => 'Medido por muestreo en farmacias institucionales.', 'indicador' => '% de disponibilidad. Línea de base: <60%', 'hito_fecha' => '31 de julio 2026', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'B-12', 'eje' => 'B · Vademécum', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Mecanismo de amparos y enfermedades catastróficas', 'diagnostico_objetivo' => 'Los amparos individuales drenan recursos sin planificación.', 'detalle_ejecucion' => 'Articulación con MSPBS, Poder Judicial, Ejecutivo y Legislativo. Revisión del FONARESS.', 'indicador' => 'Mecanismo vigente. N° de amparos canalizados', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Asesoría Jurídica + Gerencia de Salud + MSPBS'],
            ['codigo' => 'B-13', 'eje' => 'B · Vademécum', 'momento' => 'T4 · Meses 4–6', 'estado' => 'EN CURSO', 'titulo' => 'Modelo de seguros complementarios para enfermedades catastróficas', 'diagnostico_objetivo' => 'Brinda previsibilidad al asegurado y reduce la carga sobre el Fondo.', 'detalle_ejecucion' => 'Propuesta del consejero Insfrán Dietrich y el gerente Derlis León.', 'indicador' => 'Modelo aprobado. N° de asegurados cubiertos', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Gerencia de Salud + Asesoría Jurídica'],

            // EJE C · Contratos
            ['codigo' => 'C-01', 'eje' => 'C · Contratos', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'PENDIENTE', 'titulo' => 'Denuncia pública del caso bolsas pediátricas', 'diagnostico_objetivo' => 'Caso paradigmático del método del sincericidio.', 'detalle_ejecucion' => '223.000 bolsas para ostomizados pediátricos por G. 5.869 millones para ≈10 pacientes. Vinculación con grupo empresarial cercano a un diputado.', 'indicador' => 'Auditoría puntual concluida. Acción legal derivada', 'hito_fecha' => 'Denuncia 04/05; auditoría Bloque 2', 'responsable' => 'Presidencia + Auditoría Interna'],
            ['codigo' => 'C-13', 'eje' => 'C · Contratos', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría de Objetos de Gasto 845 y 915', 'diagnostico_objetivo' => 'El OG 845 permite pagos fuera de los límites de la Ley de Contrataciones.', 'detalle_ejecucion' => 'Gastos no reglados por Ley 2051/03 y gestión José González.', 'indicador' => 'Informe de auditoría. % de gastos reasignados', 'hito_fecha' => 'Inicio Bloque 1; cierre Bloque 2', 'responsable' => 'Auditoría Interna + Gerencia Adm. Financiera'],
            ['codigo' => 'C-14', 'eje' => 'C · Contratos', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría del uso de combustibles y flota', 'diagnostico_objetivo' => 'G. 7.200 millones en 2022 para 133 vehículos, 40% fuera de servicio.', 'detalle_ejecucion' => 'Incluye viajes en avión injustificados de funcionarios.', 'indicador' => 'Auditoría concluida. Sistema GPS implementado', 'hito_fecha' => 'Inicio Bloque 1; sistema en 60 días', 'responsable' => 'Auditoría Interna + Administración'],
            ['codigo' => 'C-15', 'eje' => 'C · Contratos', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Intervención en áreas de apoyo del Hospital Central', 'diagnostico_objetivo' => 'Áreas críticas con abandono detectado.', 'detalle_ejecucion' => 'Lavandería, Cocina y Producción de Materiales del HC.', 'indicador' => 'Plan de acción entregado. Indicadores por área', 'hito_fecha' => 'Cierre día 30', 'responsable' => 'Presidencia + áreas operativas'],
            ['codigo' => 'C-16', 'eje' => 'C · Contratos', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Bloqueo de vulnerabilidades del sistema AOP', 'diagnostico_objetivo' => 'Vulnerabilidad que permite borrar deudas patronales y agregar antigüedad fraudulenta.', 'detalle_ejecucion' => 'Coordinación con SET y MTESS.', 'indicador' => 'Vulnerabilidades inhabilitadas. N° de fiscalizaciones cruzadas', 'hito_fecha' => 'Días 15–30 para inhabilitación', 'responsable' => 'TI + Auditoría Interna + Recaudaciones'],
            ['codigo' => 'C-02', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría puntual del caso enzalutamida', 'diagnostico_objetivo' => 'Referencia metodológica para programación basada en consumo histórico.', 'detalle_ejecucion' => 'Oncológico cáncer de próstata: comprado para 24 meses, consumido en 6. Costo unitario G. 40 millones. 110.000 dosis solicitadas vs. 15.000 consumidas.', 'indicador' => 'Informe de auditoría. Ahorro proyectado anual', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría Interna + Gerencia de Salud'],
            ['codigo' => 'C-03', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría del sistema antiincendio del Hospital Central', 'diagnostico_objetivo' => 'Caso de gravedad excepcional: habiendo vidas de por medio.', 'detalle_ejecucion' => 'Adquirido por G. 9.000 millones, no se activó durante el incendio del 18/02/2026. Sin póliza vigente.', 'indicador' => 'Informe entregado. Garantías ejecutadas', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría Interna + Asesoría Jurídica'],
            ['codigo' => 'C-04', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría y anulación de la licitación de ascensores (Renfe S.A.)', 'diagnostico_objetivo' => 'Adjudicada tres veces a la oferta más cara, firma con antecedentes judiciales.', 'detalle_ejecucion' => 'Recomendación de la DNCP (Agustín Encina). Firma operaba sin contrato vigente.', 'indicador' => 'Sumario abierto. Adjudicación anulada', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Presidencia + Asesoría Jurídica + Logística'],
            ['codigo' => 'C-05', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Reactivación de contratos de prótesis traumatológicas', 'diagnostico_objetivo' => 'Se suspenden cirugías por falta de materiales mientras contratos están sin ejecutar.', 'detalle_ejecucion' => 'Contratos con ejecución 0% a 5,5%.', 'indicador' => '% de ejecución contractual. N° de cirugías reagendadas', 'hito_fecha' => 'Días 30–45', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'C-06', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Renegociación del contrato de jardinería (G. 45.000 millones)', 'diagnostico_objetivo' => 'Contrato sobredimensionado, candidato a racionalización.', 'detalle_ejecucion' => 'Verificación de prestación efectiva, redimensionamiento o cancelación.', 'indicador' => 'Ahorro anual proyectado', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Logística + Asesoría Jurídica'],
            ['codigo' => 'C-07', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Renegociación de higienización y textiles (G. 160.000 millones)', 'diagnostico_objetivo' => 'Servicio crítico pero con presunción de sobredimensionamiento.', 'detalle_ejecucion' => 'Higienización, desinfección y textiles hospitalarios.', 'indicador' => 'Servicio mantenido; ahorro identificado', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Logística + áreas usuarias'],
            ['codigo' => 'C-08', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Renegociación de biohigiene del área central (G. 270.000 millones)', 'diagnostico_objetivo' => 'Es el contrato individual más grande del trío sobredimensionado.', 'detalle_ejecucion' => 'Biohigiene y prevención sanitaria del área central.', 'indicador' => '% de reducción contractual', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Logística'],
            ['codigo' => 'C-09', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Verificación cruzada de prestación efectiva (todos los servicios)', 'diagnostico_objetivo' => 'Sin verificación cruzada no se detecta la sobrefacturación.', 'detalle_ejecucion' => 'Cruzar lo facturado contra lo efectivamente recibido en cada contrato.', 'indicador' => '% de contratos verificados. Diferencia detectada', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría Interna + áreas usuarias'],
            ['codigo' => 'C-10', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría de tercerización en Caaguazú y Campo 9', 'diagnostico_objetivo' => 'Un único fiscalizador médico para miles de procedimientos.', 'detalle_ejecucion' => 'Incremento anómalo de ocupación entre diciembre y febrero en terapia intensiva.', 'indicador' => 'Auditoría concluida. Fiscalización reforzada', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría Interna + Gerencia de Salud'],
            ['codigo' => 'C-11', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Revisión de tercerización en el Chaco', 'diagnostico_objetivo' => 'El Chaco opera bajo régimen monopólico con incrementos excesivos.', 'detalle_ejecucion' => 'Definición: infraestructura propia vs. tercerización inteligente.', 'indicador' => 'Auditoría concluida. Modelo definido', 'hito_fecha' => 'Días 30–60; modelo en mes 6', 'responsable' => 'Auditoría + Gerencia de Salud + Presidencia'],
            ['codigo' => 'C-12', 'eje' => 'C · Contratos', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Decisión sobre los Elefantes Blancos', 'diagnostico_objetivo' => 'Adquisiciones sin justificación técnica ni factibilidad financiera.', 'detalle_ejecucion' => 'Quirófanos modulares, plantas de oxígeno, equipos de basura hospitalaria, paneles solares, robots desinfectantes.', 'indicador' => 'N° de casos resueltos', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Asesoría Jurídica + áreas usuarias'],

            // EJE D · Prestaciones
            ['codigo' => 'D-01', 'eje' => 'D · Prestaciones', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Cierre del conflicto con Cardiología/Hemodinamia', 'diagnostico_objetivo' => 'Primer conflicto laboral resuelto mediante diálogo.', 'detalle_ejecucion' => '16 especialistas. Compromiso de mejora salarial y reemplazo de angiógrafos en 3 meses.', 'indicador' => 'Conflicto resuelto. Mejora salarial y equipos en plazo', 'hito_fecha' => 'Cerrado 28/04; cumplimiento ≈ 28/07', 'responsable' => 'Presidencia + Gerencia de Salud + RRHH'],
            ['codigo' => 'D-02', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Plan piloto en Endocrinología (lista de espera)', 'diagnostico_objetivo' => 'Lista de espera específica más crítica: más de 7.000 asegurados.', 'detalle_ejecucion' => 'Atención intensiva para reducir de 7.000 a menos de 1.000 pacientes.', 'indicador' => 'Pacientes en lista. Meta: <1.000 en una semana', 'hito_fecha' => 'Días 30–45', 'responsable' => 'Gerencia de Salud + jefes de servicio'],
            ['codigo' => 'D-03', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Replicación del modelo a 3–5 especialidades', 'diagnostico_objetivo' => 'Convertir el piloto en programa.', 'detalle_ejecucion' => 'Cardiología, oncología, neurología, traumatología u otras que defina el mapeo.', 'indicador' => 'Pacientes en espera por especialidad. Reducción %', 'hito_fecha' => 'Días 30–60; sostenido hasta mes 9', 'responsable' => 'Gerencia de Salud'],
            ['codigo' => 'D-04', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Reformulación del modelo de Call Center', 'diagnostico_objetivo' => 'El modelo actual no segmenta ni resuelve eficientemente.', 'detalle_ejecucion' => 'Segmentación por complejidad y regiones/micro-redes; cupos fuera del Call Center.', 'indicador' => 'Tasa de resolución en primer contacto. Tiempo de espera', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Salud + TI'],
            ['codigo' => 'D-05', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Acuerdo con MSPBS para intercambio de capacidades', 'diagnostico_objetivo' => 'Complementar capacidades en especialidades críticas.', 'detalle_ejecucion' => 'Oncología, nefrología, imagenología, laboratorio.', 'indicador' => 'Sistema piloto de costeo y compensación operativo', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Presidencia + MSPBS'],
            ['codigo' => 'D-06', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Turnos extendidos hasta 24 horas', 'diagnostico_objetivo' => 'Aumentar capacidad de atención sin nueva infraestructura.', 'detalle_ejecucion' => 'Medicina interna, medicina familiar, pediatría y gineco-obstetricia en hospitales de mayor volumen.', 'indicador' => 'N° de consultas adicionales por semana', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Gerencia de Salud'],
            ['codigo' => 'D-07', 'eje' => 'D · Prestaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Reactivación y ampliación de telemedicina', 'diagnostico_objetivo' => 'Seguimiento de crónicos y post-alta sin saturar consultorios.', 'detalle_ejecucion' => 'Medicina virtual anunciada por el nuevo presidente.', 'indicador' => 'N° de consultas virtuales mensuales', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Gerencia de Salud + TI'],
            ['codigo' => 'D-08', 'eje' => 'D · Prestaciones', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Diseño del Sistema Integral de Atención al Usuario', 'diagnostico_objetivo' => 'Presencia presencial en los grandes hospitales.', 'detalle_ejecucion' => 'Sistema diseñado. Despliegue en mes 4–6.', 'indicador' => 'Sistema diseñado. Despliegue en mes 4–6', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Gerencia de Salud + Org. y Calidad'],
            ['codigo' => 'D-09', 'eje' => 'D · Prestaciones', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Sistema 24/7/365 de Respuesta ante Emergencias', 'diagnostico_objetivo' => 'Cobertura completa de urgencias.', 'detalle_ejecucion' => 'Sistema propuesto. Despliegue en mes 4–6.', 'indicador' => 'Sistema propuesto. Despliegue en mes 4–6', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Gerencia de Salud'],
            ['codigo' => 'D-10', 'eje' => 'D · Prestaciones', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Mapeo formal de la cartera asistencial', 'diagnostico_objetivo' => 'Conocer la capacidad real para planificar.', 'detalle_ejecucion' => 'Mapa completo entregado.', 'indicador' => 'Mapa completo entregado', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Planificación + Gerencia de Salud'],
            ['codigo' => 'D-11', 'eje' => 'D · Prestaciones', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Despliegue de Autofarma y RAPIDOC', 'diagnostico_objetivo' => 'Descongestionar farmacias y consultorios.', 'detalle_ejecucion' => 'Puntos operativos y atenciones mensuales.', 'indicador' => 'N° de puntos operativos. Atenciones mensuales', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'D-12', 'eje' => 'D · Prestaciones', 'momento' => 'T4 · Meses 4–6', 'estado' => 'EN CURSO', 'titulo' => 'Reemplazo y ampliación de angiógrafos', 'diagnostico_objetivo' => 'Equipamiento crítico obsoleto: 10 años de antigüedad, sin repuestos.', 'detalle_ejecucion' => 'Solo dos equipos de hemodinamia para 2 millones de beneficiarios; estándar sugiere uno cada 400.000.', 'indicador' => 'Equipos instalados. Procedimientos habilitados', 'hito_fecha' => 'Mes 4–6 (compromiso de 3 meses desde 28/04)', 'responsable' => 'Presidencia + Logística'],
            ['codigo' => 'D-13', 'eje' => 'D · Prestaciones', 'momento' => 'T5 · Meses 7–9', 'estado' => 'PENDIENTE', 'titulo' => 'Reducción de la lista de espera quirúrgica a ≤60 días', 'diagnostico_objetivo' => 'Meta final de prestaciones al cierre del plan.', 'detalle_ejecucion' => 'Días máximos de espera quirúrgica.', 'indicador' => 'Días máximos de espera quirúrgica ≤ 60 días', 'hito_fecha' => 'Mes 9', 'responsable' => 'Gerencia de Salud'],

            // EJE E · Finanzas
            ['codigo' => 'E-01', 'eje' => 'E · Finanzas', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Inventario completo de pasivos', 'diagnostico_objetivo' => 'Base para toda la estrategia financiera.', 'detalle_ejecucion' => 'Documento entregado día 30.', 'indicador' => 'Documento entregado día 30', 'hito_fecha' => 'Día 30', 'responsable' => 'Gerencia Adm. y Financiera'],
            ['codigo' => 'E-02', 'eje' => 'E · Finanzas', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Análisis del flujo de caja real proyectado', 'diagnostico_objetivo' => 'Contraste con presentaciones de gestiones previas.', 'detalle_ejecucion' => 'Flujo entregado día 30.', 'indicador' => 'Flujo entregado día 30', 'hito_fecha' => 'Día 30', 'responsable' => 'Planificación + Tesorería'],
            ['codigo' => 'E-03', 'eje' => 'E · Finanzas', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Estrategia de manejo de deuda con horizonte 20–30 años', 'diagnostico_objetivo' => 'Emisión de títulos, esquemas fiduciarios, instrumentos del Fondo de Jubilaciones.', 'detalle_ejecucion' => 'Estrategia presentada al Consejo.', 'indicador' => 'Estrategia presentada al Consejo', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Presidencia + Asesoría Financiera'],
            ['codigo' => 'E-05', 'eje' => 'E · Finanzas', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'PAC dinámico articulado con flujo y presupuesto', 'diagnostico_objetivo' => 'Fin del PAC de emergencias como práctica institucional.', 'detalle_ejecucion' => 'Programación Estratégica de Contrataciones.', 'indicador' => 'PAC dinámico operativo', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Planificación + Logística'],
            ['codigo' => 'E-06', 'eje' => 'E · Finanzas', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Evaluación de la cartera de inversiones financieras', 'diagnostico_objetivo' => 'CDA con tasas inferiores al promedio.', 'detalle_ejecucion' => 'Rendimientos efectivos, vencimientos, negociación con bancos.', 'indicador' => 'Mejora de rendimiento promedio', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Gerencia Financiera'],
            ['codigo' => 'E-07', 'eje' => 'E · Finanzas', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Mesa de diálogo con proveedores farmacéuticos', 'diagnostico_objetivo' => 'Reconstruir relación de confianza en marco del nuevo vademécum.', 'detalle_ejecucion' => 'Mesa instalada. Proveedores participantes.', 'indicador' => 'Mesa instalada. Proveedores participantes', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Gerencia de Salud + Logística'],
            ['codigo' => 'E-12', 'eje' => 'E · Finanzas', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Intervención del sistema de recaudaciones AOP', 'diagnostico_objetivo' => 'Aumentar el ingreso disminuyendo la evasión.', 'detalle_ejecucion' => 'Mejora en la recaudación por inhabilitación de vulnerabilidades.', 'indicador' => '% de mejora en recaudación', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Recaudaciones + TI'],
            ['codigo' => 'E-04', 'eje' => 'E · Finanzas', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Bonos Perpetuos para deuda histórica del Estado', 'diagnostico_objetivo' => 'Canalizar deuda de USD 600–640 millones.', 'detalle_ejecucion' => 'Propuesta formal al MEF.', 'indicador' => 'Propuesta formal al MEF', 'hito_fecha' => 'Días 60–90', 'responsable' => 'Presidencia + MEF'],
            ['codigo' => 'E-08', 'eje' => 'E · Finanzas', 'momento' => 'T3 · Días 61–100', 'estado' => 'EN CURSO', 'titulo' => 'Plan Integral de Reingeniería Financiera', 'diagnostico_objetivo' => 'Documento rector de sostenibilidad.', 'detalle_ejecucion' => 'Instrumentos de largo plazo, calendario de pagos, calidad de gasto, mapa de canillas.', 'indicador' => 'Plan presentado al Consejo y al PE', 'hito_fecha' => 'Días 75–90', 'responsable' => 'Presidencia + Planificación'],
            ['codigo' => 'E-09', 'eje' => 'E · Finanzas', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Adecuaciones legales y reglamentarias', 'diagnostico_objetivo' => 'Titulación de deuda, Art. 807 CC, leyes 5.074/13 y 5.102/13.', 'detalle_ejecucion' => 'Propuestas elevadas a las instancias correspondientes.', 'indicador' => 'Propuestas elevadas', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Asesoría Jurídica'],
            ['codigo' => 'E-10', 'eje' => 'E · Finanzas', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Ejecución del primer tramo de reingeniería', 'diagnostico_objetivo' => 'Pasar del papel a la ejecución.', 'detalle_ejecucion' => 'Tramo ejecutado según calendario.', 'indicador' => 'Tramo ejecutado según calendario', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Presidencia + áreas financieras'],
            ['codigo' => 'E-11', 'eje' => 'E · Finanzas', 'momento' => 'T5 · Meses 7–9', 'estado' => 'EN CURSO', 'titulo' => 'Reducción del déficit operativo a USD 10–12 M', 'diagnostico_objetivo' => 'De USD 20–23M mensuales a la mitad mediante cierre de canillas.', 'detalle_ejecucion' => 'Déficit mensual medido.', 'indicador' => 'Déficit mensual medido (USD 10-12M)', 'hito_fecha' => 'Mes 9', 'responsable' => 'Presidencia + Gerencia Financiera'],
            ['codigo' => 'E-13', 'eje' => 'E · Finanzas', 'momento' => 'TX · Transversal', 'estado' => 'EN CURSO', 'titulo' => 'Coordinación con SET y MTESS contra evasión', 'diagnostico_objetivo' => 'Cada punto porcentual recuperado equivale a millones de dólares.', 'detalle_ejecucion' => 'Primeros resultados a 90 días.', 'indicador' => '% de evasión reducida', 'hito_fecha' => 'Permanente', 'responsable' => 'Recaudaciones + SET + MTESS'],

            // EJE F · Inmobiliario
            ['codigo' => 'F-01', 'eje' => 'F · Inmobiliario', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'PENDIENTE', 'titulo' => 'Anuncio de la auditoría patrimonial interinstitucional', 'diagnostico_objetivo' => '776+ propiedades con rentabilidad de 0,04% anual.', 'detalle_ejecucion' => 'Con RGP, Escribanía Mayor de Gobierno e IGM.', 'indicador' => 'Auditoría iniciada. Plazo 90 días', 'hito_fecha' => 'Anuncio 18/05; 90 días', 'responsable' => 'Presidencia + Min. Alberto Cabrera'],
            ['codigo' => 'F-03', 'eje' => 'F · Inmobiliario', 'momento' => 'T1 · Días 1–30', 'estado' => 'EN CURSO', 'titulo' => 'Postura del Consejo sobre el reglamento de enajenación', 'diagnostico_objetivo' => 'Reglamentación remitida por la Superintendencia de Jubilaciones.', 'detalle_ejecucion' => 'Postura oficial adoptada por el Consejo.', 'indicador' => 'Postura oficial adoptada', 'hito_fecha' => '25 de mayo 2026', 'responsable' => 'Consejo de Administración'],
            ['codigo' => 'F-02', 'eje' => 'F · Inmobiliario', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Avance de la auditoría patrimonial', 'diagnostico_objetivo' => 'Inventario verificado de las 776+ propiedades.', 'detalle_ejecucion' => 'Cruce con registros públicos.', 'indicador' => '% de propiedades inventariadas', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Min. Cabrera + RGP + EMG + IGM'],
            ['codigo' => 'F-04', 'eje' => 'F · Inmobiliario', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Análisis costo-mantenimiento vs. ingreso por propiedad', 'diagnostico_objetivo' => 'Clasificar entre rentables, mantenibles y enajenables.', 'detalle_ejecucion' => 'Clasificación completa.', 'indicador' => 'Clasificación completa realizada', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría + Gerencia Financiera'],
            ['codigo' => 'F-05', 'eje' => 'F · Inmobiliario', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Auditoría de las 40 estancias de Teniente Ochoa', 'diagnostico_objetivo' => '208.000 hectáreas con contratos de 20 años próximos a vencer.', 'detalle_ejecucion' => 'Más de 400.000 hectáreas totales del IPS en el Chaco.', 'indicador' => 'Informe de auditoría. Modelo de renovación', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Auditoría + Presidencia'],
            ['codigo' => 'F-06', 'eje' => 'F · Inmobiliario', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Revisión de contratos de favor (Hotel Guaraní y otros)', 'diagnostico_objetivo' => 'Contratos con renegociación a escondidas documentada.', 'detalle_ejecucion' => 'Incluye Manzana T, Hotel Acaray, Edificio Urundey, Edificio Yukyry, Fracción Isla de Francia.', 'indicador' => 'Contratos revisados. Ahorro por renegociación', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Asesoría Jurídica + Gerencia Financiera'],
            ['codigo' => 'F-07', 'eje' => 'F · Inmobiliario', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Aprobación del reglamento de enajenación', 'diagnostico_objetivo' => 'Contrastado y mejorado respecto a la propuesta de la Superintendencia.', 'detalle_ejecucion' => 'Reglamento aprobado por el Consejo.', 'indicador' => 'Reglamento aprobado por el Consejo', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Consejo de Administración'],
            ['codigo' => 'F-08', 'eje' => 'F · Inmobiliario', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Plan de Monetización Estratégica de la cartera', 'diagnostico_objetivo' => 'Cartera mantenible, reactivable y enajenable.', 'detalle_ejecucion' => 'Plan aprobado con cronograma.', 'indicador' => 'Plan aprobado con cronograma', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Gerencia Financiera + Presidencia'],
            ['codigo' => 'F-09', 'eje' => 'F · Inmobiliario', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Revalorización de alquileres (de 2 a 5 de cada 10)', 'diagnostico_objetivo' => 'De cada 10 guaraníes que debería cobrar, apenas recibe 2.', 'detalle_ejecucion' => 'Ingreso actual: Gs. 400 millones mensuales para toda la cartera.', 'indicador' => 'Ingreso por alquileres. Meta: triplicar', 'hito_fecha' => 'Meses 4–6 (meta intermedia)', 'responsable' => 'Gerencia Financiera'],
            ['codigo' => 'F-10', 'eje' => 'F · Inmobiliario', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Primeras operaciones de monetización', 'diagnostico_objetivo' => 'Generar ingresos por activos improductivos.', 'detalle_ejecucion' => 'N° de operaciones. Monto generado.', 'indicador' => 'N° de operaciones. Monto generado', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Gerencia Financiera + Jurídica'],
            ['codigo' => 'F-11', 'eje' => 'F · Inmobiliario', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Modificación especial del Art. 807 del Código Civil', 'diagnostico_objetivo' => 'Contratos de locación con inversión más allá de 20 años.', 'detalle_ejecucion' => 'Propuesta legislativa elevada.', 'indicador' => 'Propuesta legislativa elevada', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Asesoría Jurídica'],

            // EJE G · Jubilaciones
            ['codigo' => 'G-01', 'eje' => 'G · Jubilaciones', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Constatación del superávit del Fondo de Jubilaciones', 'diagnostico_objetivo' => 'El problema financiero está en Salud, no en Jubilaciones.', 'detalle_ejecucion' => 'Confirmado al inicio de la gestión.', 'indicador' => 'Informe de constatación', 'hito_fecha' => '24/04/2026', 'responsable' => 'Presidencia'],
            ['codigo' => 'G-02', 'eje' => 'G · Jubilaciones', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría de jubilaciones graciosas', 'diagnostico_objetivo' => 'Viudas múltiples, beneficios irregulares.', 'detalle_ejecucion' => 'Informe de auditoría. Monto recuperable.', 'indicador' => 'Informe de auditoría. Monto recuperable', 'hito_fecha' => 'Inicio Bloque 1; informe mes 3', 'responsable' => 'Auditoría Interna'],
            ['codigo' => 'G-03', 'eje' => 'G · Jubilaciones', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría del crecimiento anómalo de reposos', 'diagnostico_objetivo' => 'Reposos médicos con incremento sospechoso.', 'detalle_ejecucion' => 'Informe de auditoría concluido.', 'indicador' => 'Informe de auditoría', 'hito_fecha' => 'Inicio Bloque 1', 'responsable' => 'Auditoría Interna'],
            ['codigo' => 'G-04', 'eje' => 'G · Jubilaciones', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Auditoría de beneficios pagados a fallecidos', 'diagnostico_objetivo' => 'Pagos a derechohabientes fallecidos.', 'detalle_ejecucion' => 'N° de casos. Monto involucrado.', 'indicador' => 'N° de casos. Monto involucrado', 'hito_fecha' => 'Inicio Bloque 1', 'responsable' => 'Auditoría Interna'],
            ['codigo' => 'G-05', 'eje' => 'G · Jubilaciones', 'momento' => 'T1 · Días 1–30', 'estado' => 'PENDIENTE', 'titulo' => 'Verificación del flujo financiero para pago de jubilaciones', 'diagnostico_objetivo' => 'Asegurar continuidad del pago.', 'detalle_ejecucion' => 'Flujo verificado para el resto del año.', 'indicador' => 'Flujo verificado para el resto del año', 'hito_fecha' => 'Día 30', 'responsable' => 'Gerencia Financiera + Tesorería'],
            ['codigo' => 'G-06', 'eje' => 'G · Jubilaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Verificación y actualización de la Tasa Actuarial', 'diagnostico_objetivo' => 'Asegurar sustentabilidad del Fondo.', 'detalle_ejecucion' => 'Tasa actualizada adoptada.', 'indicador' => 'Tasa actualizada adoptada', 'hito_fecha' => 'Días 45–60', 'responsable' => 'Gerencia Financiera + actuario'],
            ['codigo' => 'G-07', 'eje' => 'G · Jubilaciones', 'momento' => 'T2 · Días 31–60', 'estado' => 'PENDIENTE', 'titulo' => 'Revisión del Art. 4° de la Ley 5.655/16 (40% rentas Fondo Jub.)', 'diagnostico_objetivo' => 'Destino del 40% de las rentas del Fondo de Jubilaciones.', 'detalle_ejecucion' => 'Propuesta legislativa elaborada.', 'indicador' => 'Propuesta legislativa elaborada', 'hito_fecha' => 'Días 45–60; propuesta en mes 7–9', 'responsable' => 'Asesoría Jurídica + Gerencia Financiera'],
            ['codigo' => 'G-08', 'eje' => 'G · Jubilaciones', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Habilitación de nuevos instrumentos de inversión', 'diagnostico_objetivo' => 'Mejorar el rendimiento de las reservas.', 'detalle_ejecucion' => 'Nuevos instrumentos habilitados. Rendimiento mejorado.', 'indicador' => 'Nuevos instrumentos habilitados', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Gerencia Financiera'],
            ['codigo' => 'G-09', 'eje' => 'G · Jubilaciones', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Diálogo paramétrico con jubilados y asegurados', 'diagnostico_objetivo' => 'Construir legitimidad para eventuales reformas.', 'detalle_ejecucion' => 'Mesas de diálogo instaladas.', 'indicador' => 'Mesas de diálogo instaladas', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Presidencia + Comunicación'],
            ['codigo' => 'G-10', 'eje' => 'G · Jubilaciones', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Reforma del Reglamento de Caja de Préstamos', 'diagnostico_objetivo' => 'Préstamos a jubilados próximos a su fallecimiento.', 'detalle_ejecucion' => 'Reglamento reformado.', 'indicador' => 'Reglamento reformado', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Asesoría Jurídica + Gerencia Financiera'],
            ['codigo' => 'G-11', 'eje' => 'G · Jubilaciones', 'momento' => 'T5 · Meses 7–9', 'estado' => 'PENDIENTE', 'titulo' => 'Propuesta de Superintendencia de Pensiones', 'diagnostico_objetivo' => 'Marco regulatorio para el sistema de pensiones.', 'detalle_ejecucion' => 'Propuesta elevada.', 'indicador' => 'Propuesta elevada', 'hito_fecha' => 'Mes 9', 'responsable' => 'Presidencia + Asesoría Jurídica'],

            // EJE H · Rec. Humanos
            ['codigo' => 'H-01', 'eje' => 'H · Rec. Humanos', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EJECUTADO', 'titulo' => 'Acuerdo Cardiología Intervencionista/Hemodinamia', 'diagnostico_objetivo' => '16 especialistas en conflicto por salarios y equipamiento.', 'detalle_ejecucion' => 'Compromiso de mejora salarial y reemplazo de angiógrafos.', 'indicador' => 'Acuerdo cumplido. Salarios ajustados', 'hito_fecha' => 'Cerrado 28/04; cumplimiento ≈ 28/07', 'responsable' => 'Presidencia + RRHH'],
            ['codigo' => 'H-02', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'EN CURSO', 'titulo' => 'Ajuste salarial dirigido a especialistas críticos', 'diagnostico_objetivo' => 'Más de 1.500 médicos perciben Gs. 3.400.000 mensuales. Hemodinamia y cardiología intervencionista entre Gs. 3.800.000 y Gs. 4.500.000.', 'detalle_ejecucion' => 'Ajuste presupuestado.', 'indicador' => 'N° de especialistas con ajuste. Monto promedio', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'Presidencia + RRHH + Gerencia Financiera'],
            ['codigo' => 'H-03', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Evaluación de dotaciones por servicio', 'diagnostico_objetivo' => 'Crecimiento de 16.652 (2017) a 26.173 (2022) funcionarios (+57%). Crecimiento de asegurados: 7%.', 'detalle_ejecucion' => 'Dotaciones optimizadas por servicio.', 'indicador' => 'Dotaciones optimizadas por servicio', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'RRHH + Planificación'],
            ['codigo' => 'H-04', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Descontratación selectiva', 'diagnostico_objetivo' => 'Cerca del 48,6% del personal corresponde a contratados.', 'detalle_ejecucion' => 'N° de contratos no renovados.', 'indicador' => 'N° de contratos no renovados', 'hito_fecha' => 'Meses 4–6 (gradual)', 'responsable' => 'RRHH + Asesoría Jurídica'],
            ['codigo' => 'H-05', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Plan de Salud Mental y respuesta a denuncias de SINADIPS', 'diagnostico_objetivo' => 'Denuncias de acoso laboral planteadas formalmente.', 'detalle_ejecucion' => 'Plan approved. Denuncias procesadas.', 'indicador' => 'Plan aprobado. Denuncias procesadas', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'RRHH + Gerencia de Salud'],
            ['codigo' => 'H-06', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Plan de formación continua y dignificación del médico', 'diagnostico_objetivo' => 'Formación y retención de talento médico.', 'detalle_ejecucion' => 'Plan piloto lanzado.', 'indicador' => 'Plan piloto lanzado', 'hito_fecha' => 'Meses 4–6 para piloto; permanente', 'responsable' => 'Gerencia de Salud + RRHH'],
            ['codigo' => 'H-07', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Reforma del régimen del personal de atención al público', 'diagnostico_objetivo' => 'Mejorar la experiencia del asegurado.', 'detalle_ejecucion' => 'Reglamento reformado.', 'indicador' => 'Reglamento reformado', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'RRHH + Org. y Calidad'],
            ['codigo' => 'H-08', 'eje' => 'H · Rec. Humanos', 'momento' => 'T4 · Meses 4–6', 'estado' => 'PENDIENTE', 'titulo' => 'Revisión del régimen de guardias activas y pasivas', 'diagnostico_objetivo' => 'Declaraciones sobre guardias pasivas generaron conflicto.', 'detalle_ejecucion' => 'Régimen revisado y aprobado.', 'indicador' => 'Régimen revisado y aprobado', 'hito_fecha' => 'Meses 4–6', 'responsable' => 'RRHH + Gerencia de Salud'],

            // EJE I · Comunicación
            ['codigo' => 'I-01', 'eje' => 'I · Comunicación', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EN CURSO', 'titulo' => 'Visitas territoriales sin agenda previa', 'diagnostico_objetivo' => 'Diagnóstico de campo sin filtros. Impacto comunicacional.', 'detalle_ejecucion' => 'Realizados: IPS Tebicuary, Hospital Regional de Encarnación, IPS Fram, Chaco.', 'indicador' => 'N° de visitas realizadas', 'hito_fecha' => 'Permanente', 'responsable' => 'Presidencia'],
            ['codigo' => 'I-02', 'eje' => 'I · Comunicación', 'momento' => 'T0 · Ejecutado / En Curso', 'estado' => 'EN CURSO', 'titulo' => 'Conferencias con presencia de auditores del Ejecutivo', 'diagnostico_objetivo' => 'Señal de transparencia y de respaldo político.', 'detalle_ejecucion' => 'Como ocurrió el 18/05 en el anuncio del vademécum.', 'indicador' => 'N° de conferencias con auditores presentes', 'hito_fecha' => 'Permanente', 'responsable' => 'Presidencia + Dirección de Prensa'],
            ['codigo' => 'I-03', 'eje' => 'I · Comunicación', 'momento' => 'T2 · Días 31–60', 'estado' => 'EN CURSO', 'titulo' => 'Creación de la Dirección de Prensa del IPS', 'diagnostico_objetivo' => 'En toda la historia del IPS nunca se creó una Dirección de Prensa.', 'detalle_ejecucion' => 'No tienen móvil, presupuesto ni tecnología según el Dr. Fretes.', 'indicador' => 'Dirección creada y operativa', 'hito_fecha' => 'Días 30–60', 'responsable' => 'Presidencia'],
            ['codigo' => 'I-04', 'eje' => 'I · Comunicación', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Encuesta institucional de satisfacción del asegurado', 'diagnostico_objetivo' => 'Metodología nacional.', 'detalle_ejecucion' => 'Encuesta realizada.', 'indicador' => 'Encuesta realizada. N° de respuestas', 'hito_fecha' => 'Días 75–100', 'responsable' => 'Org. y Calidad + Comunicación'],
            ['codigo' => 'I-07', 'eje' => 'I · Comunicación', 'momento' => 'T3 · Días 61–100', 'estado' => 'PENDIENTE', 'titulo' => 'Estado Institucional de los 100 días', 'diagnostico_objetivo' => 'Balance verificable público.', 'detalle_ejecucion' => 'Nuevo vademécum, canillas cerradas, stock recuperado, listas de espera.', 'indicador' => 'Presentación realizada. Cobertura mediática', 'hito_fecha' => 'Días 95–100', 'responsable' => 'Presidencia + Planificación'],
            ['codigo' => 'I-08', 'eje' => 'I · Comunicación', 'momento' => 'T5 · Meses 7–9', 'estado' => 'PENDIENTE', 'titulo' => 'Informe de Gestión 9 meses', 'diagnostico_objetivo' => 'Cierre del ciclo con rendición de cuentas.', 'detalle_ejecucion' => 'Documento final consolidado.', 'indicador' => 'Informe de gestión presentado', 'hito_fecha' => 'Enero 2027', 'responsable' => 'Presidencia + Planificación'],
            ['codigo' => 'I-05', 'eje' => 'I · Comunicación', 'momento' => 'TX · Transversal', 'estado' => 'EN CURSO', 'titulo' => 'Diálogo permanente con UNJP, ANAIPS y gremios', 'diagnostico_objetivo' => 'Gestión de actores clave para la legitimidad.', 'detalle_ejecucion' => 'SINADIPS, ATIPS, Asociación Médica del IPS.', 'indicador' => 'N° de reuniones. Reclamos canalizados', 'hito_fecha' => 'Permanente', 'responsable' => 'Presidencia + Dirección de Prensa'],
            ['codigo' => 'I-06', 'eje' => 'I · Comunicación', 'momento' => 'TX · Transversal', 'estado' => 'EN CURSO', 'titulo' => 'Reuniones quincenales con el Presidente Peña', 'diagnostico_objetivo' => 'Sostener el respaldo del Poder Ejecutivo.', 'detalle_ejecucion' => 'Ejecutadas 29/04 y reafirmado 16/05.', 'indicador' => 'Cadencia mantenida', 'hito_fecha' => 'Permanente', 'responsable' => 'Presidencia'],
        ];
    }
}
