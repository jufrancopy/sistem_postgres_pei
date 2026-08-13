<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PlanMaestro\PlanMaestro;
use App\Models\PlanMaestro\PlanEje;
use App\Models\PlanMaestro\PlanAccion;
use App\Models\PlanMaestro\PlanDiagnostico;
use App\Models\PlanMaestro\PlanCanilla;
use App\Models\PlanMaestro\PlanCita;

class PlanMaestroIpsSeeder extends Seeder
{
    public function run(): void
    {
        $plan = PlanMaestro::create([
            'nombre'      => 'Plan de Gestión 2026',
            'institucion' => 'Instituto de Previsión Social — República del Paraguay',
            'descripcion' => 'Primeros 100 días + Hoja de Ruta 9 meses',
            'responsable' => 'Presidencia Prof. Dr. Isaías R. Fretes Zárate',
            'periodo'     => '2026',
            'activo'      => true,
        ]);

        // ── Ejes ─────────────────────────────────────────────────────────────
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

        // ── Acciones / Iniciativas de Mejora ──────────────────────────────
        $acciones = array_merge($this->getAcciones(), $this->getAccionesCD(), $this->getAccionesEFGHI());
        foreach ($acciones as $i => $a) {
            $ejeLetter = $a['eje'];
            $targetPeiId = $peiEjeMap[$ejeLetter] ?? null;
            if ($targetPeiId && !\App\Admin\Planificacion\Pei\PeiProfile::where('id', $targetPeiId)->exists()) {
                $targetPeiId = \App\Admin\Planificacion\Pei\PeiProfile::where('level', 'action')->first()?->id;
            }

            PlanAccion::create([
                'plan_id'        => $plan->id,
                'eje_id'         => $ejeMap[$ejeLetter],
                'pei_profile_id' => $targetPeiId,
                'codigo'         => $a['id'],
                'momento'        => $a['momento'],
                'accion'         => $a['accion'],
                'justificacion'  => $a['justificacion'] ?? null,
                'kpi'            => $a['kpi'] ?? null,
                'plazo'          => $a['plazo'] ?? null,
                'responsable'    => $a['responsable'] ?? null,
                'estado'         => $a['estado'] ?? null,
                'detalle'        => $a['detalle'] ?? null,
                'orden'          => $i,
            ]);
        }

        // ── Diagnósticos ─────────────────────────────────────────────────────
        foreach ($this->getDiagnosticos() as $i => $d) {
            $diag = PlanDiagnostico::create([
                'plan_id'   => $plan->id,
                'titulo'    => $d['titulo'],
                'contenido' => $d['contenido'],
                'orden'     => $i,
            ]);
            foreach ($d['tags'] as $tag) {
                $diag->tags()->create(['tag' => $tag]);
            }
        }

        // ── Canillas ─────────────────────────────────────────────────────────
        foreach ($this->getCanillas() as $i => $c) {
            PlanCanilla::create([
                'plan_id'   => $plan->id,
                'tipo'      => $c['tipo'],
                'ejemplos'  => $c['ejemplos'],
                'estrategia'=> $c['estrategia'],
                'monto'     => $c['monto'],
                'orden'     => $i,
            ]);
        }

        // ── Citas ─────────────────────────────────────────────────────────────
        foreach ($this->getCitas() as $i => $c) {
            PlanCita::create([
                'plan_id'  => $plan->id,
                'texto'    => $c['texto'],
                'autor'    => $c['autor'],
                'fecha'    => $c['fecha'],
                'contexto' => $c['contexto'] ?? null,
                'orden'    => $i,
            ]);
        }
    }

    private function getAcciones(): array
    {
        return [
            ['id'=>'A-01','eje'=>'A','momento'=>'T0','accion'=>'Pedido formal de cargos a disposición','justificacion'=>'Quiebre simbólico con la gestión anterior y habilitación política para la renovación de cargos de confianza.','kpi'=>'% de cargos efectivamente puestos a disposición y resueltos.','plazo'=>'Hito día 1 (22/04/2026)','responsable'=>'Presidencia del IPS','estado'=>'EJECUTADO','detalle'=>'Solicitado a la totalidad de consejeros, gerentes y directores.'],
            ['id'=>'A-02','eje'=>'A','momento'=>'T0','accion'=>'Renovación de asesores de Presidencia','justificacion'=>'Conformar un primer anillo técnico alineado con la nueva conducción.','kpi'=>'Anillo de Presidencia completo y operativo.','plazo'=>'Resolución del 4 de mayo','responsable'=>'Presidencia del IPS','estado'=>'EJECUTADO 04/05','detalle'=>'Salieron Torres de Argüello, Rodríguez Álvarez, Vargas Morales, Rolón Ibarra; ingresaron Lovera Cañete, González Parra, Rodríguez Maidana y Martínez Bogado.'],
            ['id'=>'A-03','eje'=>'A','momento'=>'T0','accion'=>'Renovación de cuatro direcciones clave','justificacion'=>'Garantizar control interno robusto, planificación articulada y dirección de calidad.','kpi'=>'Direcciones operativas con planes de trabajo en 30 días.','plazo'=>'Resoluciones N° 032-018 a 032-021/2026','responsable'=>'Presidencia del IPS','estado'=>'EJECUTADO 05/05','detalle'=>'Gabinete (Néstor Carrillo Rotela), Auditoría Interna (Walter Laguardia Lovera), Planificación (Julio Franco Báez), Organización y Calidad (María Acosta Faranda).'],
            ['id'=>'A-04','eje'=>'A','momento'=>'T1','accion'=>'Insistencia formal con consejeros remanentes','justificacion'=>'La movilización de UNJP exige la renovación del Consejo.','kpi'=>'N° de procedimientos formales iniciados.','plazo'=>'Inicio Bloque 1; cierre hacia mes 6','responsable'=>'Presidencia + Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>'Procedimientos previstos en la Carta Orgánica para Jara Rojas y Argaña Contreras.'],
            ['id'=>'A-05','eje'=>'A','momento'=>'T0','accion'=>'Auditoría externa de la Contraloría General de la República','justificacion'=>'La CGR ya tenía un informe lapidario (4 de 29 observaciones aprobadas). La auditoría es control y blindaje político.','kpi'=>'Informe consolidado de la CGR. % de observaciones aceptadas.','plazo'=>'Cierre ≈ 4 de junio 2026','responsable'=>'Auditoría Interna + Presidencia + CGR','estado'=>'EN CURSO desde 04/05','detalle'=>'Sobre situación financiera, presupuestaria y patrimonial del IPS.'],
            ['id'=>'A-06','eje'=>'A','momento'=>'T2','accion'=>'Auditorías internas puntuales priorizadas','justificacion'=>'Convertir denuncias mediáticas en expedientes administrativos con sustento documental.','kpi'=>'N° de auditorías con informe final. Monto recuperado.','plazo'=>'Días 30–60','responsable'=>'Auditoría Interna (Laguardia Lovera)','estado'=>'ORDENADAS','detalle'=>'Sistema antiincendio, prótesis con ejecución cero, bolsas pediátricas, enzalutamida, licitación de ascensores, contratos sobredimensionados.'],
            ['id'=>'A-07','eje'=>'A','momento'=>'T1','accion'=>'Inventario auditado de pasivos del Fondo de Salud','justificacion'=>'Hoja de ruta del flujo financiero futuro; condición previa a cualquier negociación.','kpi'=>'Documento entregado al cierre del día 30.','plazo'=>'≈ 22 de mayo','responsable'=>'Gerencia Adm. y Financiera + Planificación','estado'=>'EN ELABORACIÓN','detalle'=>'Fiducias, cesiones 2024-2025, préstamos, deuda flotante, órdenes no contabilizadas.'],
            ['id'=>'A-08','eje'=>'A','momento'=>'T1','accion'=>'Mapeo integral de contratos activos','justificacion'=>'Insumo para la racionalización del gasto y cierre de canillas contractuales.','kpi'=>'% de contratos mapeados. N° identificados para suspensión.','plazo'=>'Cierre día 30','responsable'=>'Gerencia de Logística + Asesoría Jurídica','estado'=>'EN ELABORACIÓN','detalle'=>'Vencimientos, saldos por ejecutar, contratos regulares, irregulares y pseudo-prioritarios.'],
            ['id'=>'A-09','eje'=>'A','momento'=>'T0','accion'=>'Sesiones del Consejo transmitidas en vivo','justificacion'=>'Pilar de transparencia. Disuade la captura institucional y consolida legitimidad.','kpi'=>'N° de sesiones transmitidas / realizadas.','plazo'=>'Permanente','responsable'=>'Presidencia + Dirección de Prensa','estado'=>'EN CURSO','detalle'=>'Aplicado los días 29/04, 7/05, 10/05 y 12/05.'],
            ['id'=>'A-10','eje'=>'A','momento'=>'T3','accion'=>'Tablero de Control Institucional','justificacion'=>'Sustituye la lógica de informes mensuales por gestión en tiempo casi real.','kpi'=>'Tablero operativo con 12 indicadores estratégicos.','plazo'=>'Días 75–100','responsable'=>'Planificación + Org. y Calidad + TI','estado'=>'EN DISEÑO','detalle'=>'Stock por farmacia, listas de espera, productividad, ejecución contractual, alertas tempranas.'],
            ['id'=>'A-11','eje'=>'A','momento'=>'T3','accion'=>'Apertura del proceso de revisión de la Carta Orgánica','justificacion'=>'Modernización institucional requiere base normativa nueva. Vigente desde 1992.','kpi'=>'Mesa instalada. Agenda mínima acordada.','plazo'=>'Días 90–100','responsable'=>'Presidencia + Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>'Con representación del Consejo, gremios, academia y Poderes del Estado.'],
            ['id'=>'A-12','eje'=>'A','momento'=>'T5','accion'=>'Anteproyecto de Carta Orgánica reformada','justificacion'=>'Materializa el horizonte estructural del plan.','kpi'=>'Anteproyecto entregado al Poder Ejecutivo.','plazo'=>'Enero 2027','responsable'=>'Mesa técnica multisectorial','estado'=>'PENDIENTE','detalle'=>'Texto que pueda ser elevado al Congreso.'],
            ['id'=>'A-13','eje'=>'A','momento'=>'T5','accion'=>'Cumplimiento del 80% de observaciones de la CGR','justificacion'=>'Línea de base: 4 de 29 observaciones aprobadas (14%).','kpi'=>'% de observaciones cumplidas. Meta: 80%.','plazo'=>'Mes 9','responsable'=>'Auditoría Interna + áreas observadas','estado'=>'PENDIENTE','detalle'=>'Plan formal de respuesta a las 25 observaciones no resueltas del ejercicio 2024.'],
            ['id'=>'A-14','eje'=>'A','momento'=>'TX','accion'=>'Traslado de denuncias públicas a denuncias formales','justificacion'=>'La denuncia mediática sin traducción judicial pierde fuerza con el tiempo.','kpi'=>'N° de denuncias formales radicadas.','plazo'=>'Permanente; primera batería en Bloque 2','responsable'=>'Asesoría Jurídica + Presidencia','estado'=>'PENDIENTE','detalle'=>'Bolsas pediátricas, ascensores, antiincendio, enzalutamida, prótesis ante Ministerio Público y Contraloría.'],
            ['id'=>'B-01','eje'=>'B','momento'=>'T0','accion'=>'Taller de revisión del vademécum en Ykua Satí','justificacion'=>'Migrar la gobernanza de las compras del área administrativa al área médica.','kpi'=>'N° de productos identificados (988 al 16/05).','plazo'=>'13/05/2026','responsable'=>'Gerencia de Salud + jefes médicos','estado'=>'EJECUTADO','detalle'=>'Con jefes de servicio, directores de hospital y profesionales de blanco.'],
            ['id'=>'B-02','eje'=>'B','momento'=>'T0','accion'=>'Anuncio del retiro de 988 ítems del vademécum','justificacion'=>'El cambio del vademécum es entrar al corazón de la corrupción.','kpi'=>'Cifra final consolidada. Ahorro proyectado.','plazo'=>'Anunciado 18/05; cifra final en 30–45 días','responsable'=>'Presidencia + Gerencia de Salud','estado'=>'EN CURSO','detalle'=>'De 4.000 productos del vademécum. Conferencia con auditores del Poder Ejecutivo.'],
            ['id'=>'B-03','eje'=>'B','momento'=>'T2','accion'=>'Resolución formal del nuevo vademécum','justificacion'=>'Sin acto administrativo formal, el retiro queda como anuncio.','kpi'=>'Resolución publicada. Vademécum vigente con N° final.','plazo'=>'Días 30–45','responsable'=>'Consejo + Gerencia de Salud + Jurídica','estado'=>'PENDIENTE','detalle'=>'Establecer el nuevo vademécum institucional depurado.'],
            ['id'=>'B-04','eje'=>'B','momento'=>'T2','accion'=>'Definición del Cuadro Básico de Insumos depurado','justificacion'=>'El cuadro básico cubre insumos; ambos drenan recursos si no están depurados.','kpi'=>'Cuadro Básico vigente con protocolo de revisión anual.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Salud + sociedades científicas','estado'=>'PENDIENTE','detalle'=>'Con criterio técnico-médico, junto a sociedades científicas y academia.'],
            ['id'=>'B-05','eje'=>'B','momento'=>'T2','accion'=>'Nueva gobernanza de compras (decisión médica, no administrativa)','justificacion'=>'Las licitaciones eran definidas por el sector administrativo sin criterios médicos.','kpi'=>'Resolución vigente. % de licitaciones bajo nueva gobernanza.','plazo'=>'Días 30–45','responsable'=>'Consejo + Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>'Transferir la decisión de qué se compra hacia la Gerencia de Salud.'],
            ['id'=>'B-06','eje'=>'B','momento'=>'T2','accion'=>'Programación de compras por consumo histórico real','justificacion'=>'Las compras infladas son una de las canillas más caras y silenciosas.','kpi'=>'% de licitaciones con justificación técnica de cantidad.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Salud + Logística','estado'=>'PENDIENTE','detalle'=>'Caso enzalutamida: 15.000 dosis/mes reales vs. 110.000 solicitadas.'],
            ['id'=>'B-07','eje'=>'B','momento'=>'T2','accion'=>'Cumplimiento del plazo de 45 días para stock cero','justificacion'=>'Compromiso público del Dr. Fretes: hito de credibilidad inmediato.','kpi'=>'N° de medicamentos en stock cero. Meta: 0 al mes 4.','plazo'=>'≈ 6 de junio 2026','responsable'=>'Gerencia de Salud + Logística','estado'=>'EN CURSO','detalle'=>'150 medicamentos en stock cero. 60 ítems ya adjudicados al 13/05.'],
            ['id'=>'B-08','eje'=>'B','momento'=>'T2','accion'=>'Sistema de trazabilidad de medicamentos','justificacion'=>'La ausencia de trazabilidad habilita fugas a circuitos privados.','kpi'=>'N° de farmacias con trazabilidad activa.','plazo'=>'Piloto días 60–100; despliegue mes 4–6','responsable'=>'Gerencia de Salud + Logística + TI','estado'=>'PENDIENTE','detalle'=>'Desde recepción en parque sanitario hasta dispensación al paciente.'],
            ['id'=>'B-09','eje'=>'B','momento'=>'T2','accion'=>'Convenio CEMIT-UNA para control externo de calidad','justificacion'=>'El Laboratorio interno ha sido permisivo según diagnósticos previos.','kpi'=>'N° de muestras auditadas. % de discrepancias.','plazo'=>'Días 45–60 para convenio','responsable'=>'Gerencia de Salud + CEMIT-UNA','estado'=>'PENDIENTE','detalle'=>'Auditoría externa cruzada del Laboratorio de Control de Calidad del IPS.'],
            ['id'=>'B-10','eje'=>'B','momento'=>'T2','accion'=>'Desactivación de la licitación pendiente de G. 76.000 millones','justificacion'=>'Caso paradigmático: licitación sin justificación vs. 164 asegurados en riesgo de ceguera.','kpi'=>'Licitación desactivada. Recursos reasignados.','plazo'=>'Días 30–45','responsable'=>'Presidencia + Consejo + Logística','estado'=>'PENDIENTE','detalle'=>'Reasignación a prioridades reales como kits oftalmológicos.'],
            ['id'=>'B-11','eje'=>'B','momento'=>'T3','accion'=>'95% de disponibilidad del cuadro básico','justificacion'=>'Compromiso público del Dr. Fretes. Define el éxito de los 100 días.','kpi'=>'% de disponibilidad. Línea de base: <60%.','plazo'=>'31 de julio 2026','responsable'=>'Gerencia de Salud + Logística','estado'=>'EN CURSO','detalle'=>'Medido por muestreo en farmacias institucionales.'],
            ['id'=>'B-12','eje'=>'B','momento'=>'T4','accion'=>'Mecanismo de amparos y enfermedades catastróficas','justificacion'=>'Los amparos individuales drenan recursos sin planificación.','kpi'=>'Mecanismo vigente. N° de amparos canalizados.','plazo'=>'Meses 4–6','responsable'=>'Asesoría Jurídica + Gerencia de Salud + MSPBS','estado'=>'PENDIENTE','detalle'=>'Articulación con MSPBS, Poder Judicial, Ejecutivo y Legislativo.'],
            ['id'=>'B-13','eje'=>'B','momento'=>'T4','accion'=>'Modelo de seguros complementarios para enfermedades catastróficas','justificacion'=>'Brinda previsibilidad al asegurado y reduce la carga sobre el Fondo.','kpi'=>'Modelo aprobado. N° de asegurados cubiertos.','plazo'=>'Meses 4–6','responsable'=>'Gerencia de Salud + Asesoría Jurídica','estado'=>'ANÁLISIS PRELIMINAR ENTREGADO 13/05','detalle'=>'Propuesta del consejero Insfrán Dietrich y el gerente Derlis León.'],
        ];
    }

    private function getAccionesCD(): array
    {
        return [
            ['id'=>'C-01','eje'=>'C','momento'=>'T0','accion'=>'Denuncia pública del caso bolsas pediátricas','justificacion'=>'Caso paradigmático del método del sincericidio.','kpi'=>'Auditoría puntual concluida. Acción legal derivada.','plazo'=>'Denuncia 04/05; auditoría Bloque 2','responsable'=>'Presidencia + Auditoría Interna','estado'=>'DENUNCIADO PÚBLICAMENTE','detalle'=>'223.000 bolsas para ostomizados pediátricos por G. 5.869 millones para ≈10 pacientes.'],
            ['id'=>'C-02','eje'=>'C','momento'=>'T2','accion'=>'Auditoría puntual del caso enzalutamida','justificacion'=>'Referencia metodológica para programación basada en consumo histórico.','kpi'=>'Informe de auditoría. Ahorro proyectado anual.','plazo'=>'Días 30–60','responsable'=>'Auditoría Interna + Gerencia de Salud','estado'=>'IDENTIFICADA','detalle'=>'Oncológico cáncer de próstata: comprado para 24 meses, consumido en 6.'],
            ['id'=>'C-03','eje'=>'C','momento'=>'T2','accion'=>'Auditoría del sistema antiincendio del Hospital Central','justificacion'=>'Caso de gravedad excepcional: habiendo vidas de por medio.','kpi'=>'Informe entregado. Garantías ejecutadas.','plazo'=>'Días 30–60','responsable'=>'Auditoría Interna + Asesoría Jurídica','estado'=>'ORDENADA POR EL CONSEJO','detalle'=>'Adquirido por G. 9.000 millones, no se activó durante el incendio del 18/02/2026.'],
            ['id'=>'C-04','eje'=>'C','momento'=>'T2','accion'=>'Auditoría y anulación de la licitación de ascensores (Renfe S.A.)','justificacion'=>'Adjudicada tres veces a la oferta más cara, firma con antecedentes judiciales.','kpi'=>'Sumario abierto. Adjudicación anulada.','plazo'=>'Días 30–60','responsable'=>'Presidencia + Asesoría Jurídica + Logística','estado'=>'DENUNCIADA PÚBLICAMENTE; sumario pendiente','detalle'=>'Recomendación de la DNCP (Agustín Encina). Firma operaba sin contrato vigente.'],
            ['id'=>'C-05','eje'=>'C','momento'=>'T2','accion'=>'Reactivación de contratos de prótesis traumatológicas','justificacion'=>'Se suspenden cirugías por falta de materiales mientras contratos están sin ejecutar.','kpi'=>'% de ejecución contractual. N° de cirugías reagendadas.','plazo'=>'Días 30–45','responsable'=>'Gerencia de Salud + Logística','estado'=>'IDENTIFICADA','detalle'=>'Contratos con ejecución 0% a 5,5%.'],
            ['id'=>'C-06','eje'=>'C','momento'=>'T2','accion'=>'Renegociación del contrato de jardinería (G. 45.000 millones)','justificacion'=>'Contrato sobredimensionado, candidato a racionalización.','kpi'=>'Ahorro anual proyectado.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Logística + Asesoría Jurídica','estado'=>'IDENTIFICADA','detalle'=>'Verificación de prestación efectiva, redimensionamiento o cancelación.'],
            ['id'=>'C-07','eje'=>'C','momento'=>'T2','accion'=>'Renegociación de higienización y textiles (G. 160.000 millones)','justificacion'=>'Servicio crítico pero con presunción de sobredimensionamiento.','kpi'=>'Servicio mantenido; ahorro identificado.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Logística + áreas usuarias','estado'=>'IDENTIFICADA','detalle'=>'Higienización, desinfección y textiles hospitalarios.'],
            ['id'=>'C-08','eje'=>'C','momento'=>'T2','accion'=>'Renegociación de biohigiene del área central (G. 270.000 millones)','justificacion'=>'Es el contrato individual más grande del trío sobredimensionado.','kpi'=>'% de reducción contractual.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Logística','estado'=>'IDENTIFICADA','detalle'=>'Biohigiene y prevención sanitaria del área central.'],
            ['id'=>'C-09','eje'=>'C','momento'=>'T2','accion'=>'Verificación cruzada de prestación efectiva (todos los servicios)','justificacion'=>'Sin verificación cruzada no se detecta la sobrefacturación.','kpi'=>'% de contratos verificados. Diferencia detectada.','plazo'=>'Días 30–60','responsable'=>'Auditoría Interna + áreas usuarias','estado'=>'PENDIENTE','detalle'=>'Cruzar lo facturado contra lo efectivamente recibido en cada contrato.'],
            ['id'=>'C-10','eje'=>'C','momento'=>'T2','accion'=>'Auditoría de tercerización en Caaguazú y Campo 9','justificacion'=>'Un único fiscalizador médico para miles de procedimientos.','kpi'=>'Auditoría concluida. Fiscalización reforzada.','plazo'=>'Días 30–60','responsable'=>'Auditoría Interna + Gerencia de Salud','estado'=>'ORDENADA 12/05','detalle'=>'Incremento anómalo de ocupación entre diciembre y febrero en terapia intensiva.'],
            ['id'=>'C-11','eje'=>'C','momento'=>'T2','accion'=>'Revisión de tercerización en el Chaco','justificacion'=>'El Chaco opera bajo régimen monopólico con incrementos excesivos.','kpi'=>'Auditoría concluida. Modelo definido.','plazo'=>'Días 30–60; modelo en mes 6','responsable'=>'Auditoría + Gerencia de Salud + Presidencia','estado'=>'BAJO REVISIÓN','detalle'=>'Definición: infraestructura propia vs. tercerización inteligente.'],
            ['id'=>'C-12','eje'=>'C','momento'=>'T2','accion'=>'Decisión sobre los Elefantes Blancos','justificacion'=>'Adquisiciones sin justificación técnica ni factibilidad financiera.','kpi'=>'N° de casos resueltos.','plazo'=>'Días 30–60','responsable'=>'Asesoría Jurídica + áreas usuarias','estado'=>'PENDIENTE','detalle'=>'Quirófanos modulares, plantas de oxígeno, equipos de basura hospitalaria, paneles solares, robots desinfectantes.'],
            ['id'=>'C-13','eje'=>'C','momento'=>'T1','accion'=>'Auditoría de Objetos de Gasto 845 y 915','justificacion'=>'El OG 845 permite pagos fuera de los límites de la Ley de Contrataciones.','kpi'=>'Informe de auditoría. % de gastos reasignados.','plazo'=>'Inicio Bloque 1; cierre Bloque 2','responsable'=>'Auditoría Interna + Gerencia Adm. Financiera','estado'=>'PENDIENTE','detalle'=>'Gastos no reglados por Ley 2051/03 y gestión José González.'],
            ['id'=>'C-14','eje'=>'C','momento'=>'T1','accion'=>'Auditoría del uso de combustibles y flota','justificacion'=>'G. 7.200 millones en 2022 para 133 vehículos, 40% fuera de servicio.','kpi'=>'Auditoría concluida. Sistema GPS implementado.','plazo'=>'Inicio Bloque 1; sistema en 60 días','responsable'=>'Auditoría Interna + Administración','estado'=>'PENDIENTE','detalle'=>'Incluye viajes en avión injustificados de funcionarios.'],
            ['id'=>'C-15','eje'=>'C','momento'=>'T1','accion'=>'Intervención en áreas de apoyo del Hospital Central','justificacion'=>'Áreas críticas con abandono detectado.','kpi'=>'Plan de acción entregado. Indicadores por área.','plazo'=>'Cierre día 30','responsable'=>'Presidencia + áreas operativas','estado'=>'PENDIENTE','detalle'=>'Lavandería, Cocina y Producción de Materiales del HC.'],
            ['id'=>'C-16','eje'=>'C','momento'=>'T1','accion'=>'Bloqueo de vulnerabilidades del sistema AOP','justificacion'=>'Vulnerabilidad que permite borrar deudas patronales y agregar antigüedad fraudulenta.','kpi'=>'Vulnerabilidades inhabilitadas. N° de fiscalizaciones cruzadas.','plazo'=>'Días 15–30 para inhabilitación','responsable'=>'TI + Auditoría Interna + Recaudaciones','estado'=>'INTERVENCIÓN INICIADA','detalle'=>'Coordinación con SET y MTESS.'],
            ['id'=>'D-01','eje'=>'D','momento'=>'T0','accion'=>'Cierre del conflicto con Cardiología/Hemodinamia','justificacion'=>'Primer conflicto laboral resuelto mediante diálogo.','kpi'=>'Conflicto resuelto. Mejora salarial y equipos en plazo.','plazo'=>'Cerrado 28/04; cumplimiento ≈ 28/07','responsable'=>'Presidencia + Gerencia de Salud + RRHH','estado'=>'CERRADO 28/04; en cumplimiento','detalle'=>'16 especialistas. Compromiso de mejora salarial y reemplazo de angiógrafos en 3 meses.'],
            ['id'=>'D-02','eje'=>'D','momento'=>'T2','accion'=>'Plan piloto en Endocrinología (lista de espera)','justificacion'=>'Lista de espera específica más crítica: más de 7.000 asegurados.','kpi'=>'Pacientes en lista. Meta: <1.000 en una semana.','plazo'=>'Días 30–45','responsable'=>'Gerencia de Salud + jefes de servicio','estado'=>'EN DISEÑO','detalle'=>'Atención intensiva para reducir de 7.000 a menos de 1.000 pacientes.'],
            ['id'=>'D-03','eje'=>'D','momento'=>'T2','accion'=>'Replicación del modelo a 3–5 especialidades','justificacion'=>'Convertir el piloto en programa.','kpi'=>'Pacientes en espera por especialidad. Reducción %.','plazo'=>'Días 30–60; sostenido hasta mes 9','responsable'=>'Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>'Cardiología, oncología, neurología, traumatología u otras que defina el mapeo.'],
            ['id'=>'D-04','eje'=>'D','momento'=>'T2','accion'=>'Reformulación del modelo de Call Center','justificacion'=>'El modelo actual no segmenta ni resuelve eficientemente.','kpi'=>'Tasa de resolución en primer contacto. Tiempo de espera.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Salud + TI','estado'=>'PENDIENTE','detalle'=>'Segmentación por complejidad y regiones/micro-redes; cupos fuera del Call Center.'],
            ['id'=>'D-05','eje'=>'D','momento'=>'T2','accion'=>'Acuerdo con MSPBS para intercambio de capacidades','justificacion'=>'Complementar capacidades en especialidades críticas.','kpi'=>'Sistema piloto de costeo y compensación operativo.','plazo'=>'Días 45–60','responsable'=>'Presidencia + MSPBS','estado'=>'EN GESTIÓN','detalle'=>'Oncología, nefrología, imagenología, laboratorio.'],
            ['id'=>'D-06','eje'=>'D','momento'=>'T2','accion'=>'Turnos extendidos hasta 24 horas','justificacion'=>'Aumentar capacidad de atención sin nueva infraestructura.','kpi'=>'N° de consultas adicionales por semana.','plazo'=>'Días 45–60','responsable'=>'Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>'Medicina interna, medicina familiar, pediatría y gineco-obstetricia en hospitales de mayor volumen.'],
            ['id'=>'D-07','eje'=>'D','momento'=>'T2','accion'=>'Reactivación y ampliación de telemedicina','justificacion'=>'Seguimiento de crónicos y post-alta sin saturar consultorios.','kpi'=>'N° de consultas virtuales mensuales.','plazo'=>'Días 45–60','responsable'=>'Gerencia de Salud + TI','estado'=>'PENDIENTE','detalle'=>'Medicina virtual anunciada por el nuevo presidente.'],
            ['id'=>'D-08','eje'=>'D','momento'=>'T3','accion'=>'Diseño del Sistema Integral de Atención al Usuario','justificacion'=>'Presencia presencial en los grandes hospitales.','kpi'=>'Sistema diseñado. Despliegue en mes 4–6.','plazo'=>'Días 75–100','responsable'=>'Gerencia de Salud + Org. y Calidad','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'D-09','eje'=>'D','momento'=>'T3','accion'=>'Sistema 24/7/365 de Respuesta ante Emergencias','justificacion'=>'Cobertura completa de urgencias.','kpi'=>'Sistema propuesto. Despliegue en mes 4–6.','plazo'=>'Días 75–100','responsable'=>'Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'D-10','eje'=>'D','momento'=>'T3','accion'=>'Mapeo formal de la cartera asistencial','justificacion'=>'Conocer la capacidad real para planificar.','kpi'=>'Mapa completo entregado.','plazo'=>'Días 75–100','responsable'=>'Planificación + Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'D-11','eje'=>'D','momento'=>'T4','accion'=>'Despliegue de Autofarma y RAPIDOC','justificacion'=>'Descongestionar farmacias y consultorios.','kpi'=>'N° de puntos operativos. Atenciones mensuales.','plazo'=>'Meses 4–6','responsable'=>'Gerencia de Salud + Logística','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'D-12','eje'=>'D','momento'=>'T4','accion'=>'Reemplazo y ampliación de angiógrafos','justificacion'=>'Equipamiento crítico obsoleto: 10 años de antigüedad, sin repuestos.','kpi'=>'Equipos instalados. Procedimientos habilitados.','plazo'=>'Mes 4–6 (compromiso de 3 meses desde 28/04)','responsable'=>'Presidencia + Logística','estado'=>'COMPROMETIDO','detalle'=>'Solo dos equipos de hemodinamia para 2 millones de beneficiarios.'],
            ['id'=>'D-13','eje'=>'D','momento'=>'T5','accion'=>'Reducción de la lista de espera quirúrgica a ≤60 días','justificacion'=>'Meta final de prestaciones al cierre del plan.','kpi'=>'Días máximos de espera quirúrgica.','plazo'=>'Mes 9','responsable'=>'Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>''],
        ];
    }

    private function getAccionesEFGHI(): array
    {
        return [
            ['id'=>'E-01','eje'=>'E','momento'=>'T1','accion'=>'Inventario completo de pasivos','justificacion'=>'Base para toda la estrategia financiera.','kpi'=>'Documento entregado día 30.','plazo'=>'Día 30','responsable'=>'Gerencia Adm. y Financiera','estado'=>'EN ELABORACIÓN','detalle'=>''],
            ['id'=>'E-02','eje'=>'E','momento'=>'T1','accion'=>'Análisis del flujo de caja real proyectado','justificacion'=>'Contraste con presentaciones de gestiones previas.','kpi'=>'Flujo entregado día 30.','plazo'=>'Día 30','responsable'=>'Planificación + Tesorería','estado'=>'EN ELABORACIÓN','detalle'=>''],
            ['id'=>'E-03','eje'=>'E','momento'=>'T2','accion'=>'Estrategia de manejo de deuda con horizonte 20–30 años','justificacion'=>'Emisión de títulos, esquemas fiduciarios, instrumentos del Fondo de Jubilaciones.','kpi'=>'Estrategia presentada al Consejo.','plazo'=>'Días 45–60','responsable'=>'Presidencia + Asesoría Financiera','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-04','eje'=>'E','momento'=>'T3','accion'=>'Bonos Perpetuos para deuda histórica del Estado','justificacion'=>'Canalizar deuda de USD 600–640 millones.','kpi'=>'Propuesta formal al MEF.','plazo'=>'Días 60–90','responsable'=>'Presidencia + MEF','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-05','eje'=>'E','momento'=>'T2','accion'=>'PAC dinámico articulado con flujo y presupuesto','justificacion'=>'Fin del PAC de emergencias como práctica institucional.','kpi'=>'PAC dinámico operativo.','plazo'=>'Días 30–60','responsable'=>'Planificación + Logística','estado'=>'PENDIENTE','detalle'=>'Programación Estratégica de Contrataciones.'],
            ['id'=>'E-06','eje'=>'E','momento'=>'T2','accion'=>'Evaluación de la cartera de inversiones financieras','justificacion'=>'CDA con tasas inferiores al promedio.','kpi'=>'Mejora de rendimiento promedio.','plazo'=>'Días 45–60','responsable'=>'Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>'Rendimientos efectivos, vencimientos, negociación con bancos.'],
            ['id'=>'E-07','eje'=>'E','momento'=>'T2','accion'=>'Mesa de diálogo con proveedores farmacéuticos','justificacion'=>'Reconstruir relación de confianza en marco del nuevo vademécum.','kpi'=>'Mesa instalada. Proveedores participantes.','plazo'=>'Días 30–60','responsable'=>'Gerencia de Salud + Logística','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-08','eje'=>'E','momento'=>'T3','accion'=>'Plan Integral de Reingeniería Financiera','justificacion'=>'Documento rector de sostenibilidad.','kpi'=>'Plan presentado al Consejo y al PE.','plazo'=>'Días 75–90','responsable'=>'Presidencia + Planificación','estado'=>'EN ELABORACIÓN','detalle'=>'Instrumentos de largo plazo, calendario de pagos, calidad de gasto, mapa de canillas.'],
            ['id'=>'E-09','eje'=>'E','momento'=>'T3','accion'=>'Adecuaciones legales y reglamentarias','justificacion'=>'Titulación de deuda, Art. 807 CC, leyes 5.074/13 y 5.102/13.','kpi'=>'Propuestas elevadas.','plazo'=>'Días 75–100','responsable'=>'Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-10','eje'=>'E','momento'=>'T4','accion'=>'Ejecución del primer tramo de reingeniería','justificacion'=>'Pasar del papel a la ejecución.','kpi'=>'Tramo ejecutado según calendario.','plazo'=>'Meses 4–6','responsable'=>'Presidencia + áreas financieras','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-11','eje'=>'E','momento'=>'T5','accion'=>'Reducción del déficit operativo a USD 10–12 M','justificacion'=>'De USD 20–23M mensuales a la mitad mediante cierre de canillas.','kpi'=>'Déficit mensual medido.','plazo'=>'Mes 9','responsable'=>'Presidencia + Gerencia Financiera','estado'=>'EN CONSTRUCCIÓN','detalle'=>''],
            ['id'=>'E-12','eje'=>'E','momento'=>'T2','accion'=>'Intervención del sistema de recaudaciones AOP','justificacion'=>'Aumentar el ingreso disminuyendo la evasión.','kpi'=>'% de mejora en recaudación.','plazo'=>'Días 30–60','responsable'=>'Recaudaciones + TI','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'E-13','eje'=>'E','momento'=>'TX','accion'=>'Coordinación con SET y MTESS contra evasión','justificacion'=>'Cada punto porcentual recuperado equivale a millones de dólares.','kpi'=>'% de evasión reducida. Primeros resultados a 90 días.','plazo'=>'Permanente','responsable'=>'Recaudaciones + SET + MTESS','estado'=>'EN ARMADO','detalle'=>''],
            ['id'=>'F-01','eje'=>'F','momento'=>'T0','accion'=>'Anuncio de la auditoría patrimonial interinstitucional','justificacion'=>'776+ propiedades con rentabilidad de 0,04% anual.','kpi'=>'Auditoría iniciada. Plazo 90 días.','plazo'=>'Anuncio 18/05; 90 días','responsable'=>'Presidencia + Min. Alberto Cabrera','estado'=>'ANUNCIADA','detalle'=>'Con RGP, Escribanía Mayor de Gobierno e IGM.'],
            ['id'=>'F-02','eje'=>'F','momento'=>'T2','accion'=>'Avance de la auditoría patrimonial','justificacion'=>'Inventario verificado de las 776+ propiedades.','kpi'=>'% de propiedades inventariadas.','plazo'=>'Días 30–60','responsable'=>'Min. Cabrera + RGP + EMG + IGM','estado'=>'EN INICIO','detalle'=>'Cruce con registros públicos.'],
            ['id'=>'F-03','eje'=>'F','momento'=>'T1','accion'=>'Postura del Consejo sobre el reglamento de enajenación','justificacion'=>'Reglamentación remitida por la Superintendencia de Jubilaciones.','kpi'=>'Postura oficial adoptada.','plazo'=>'25 de mayo','responsable'=>'Consejo de Administración','estado'=>'EN ELABORACIÓN','detalle'=>''],
            ['id'=>'F-04','eje'=>'F','momento'=>'T2','accion'=>'Análisis costo-mantenimiento vs. ingreso por propiedad','justificacion'=>'Clasificar entre rentables, mantenibles y enajenables.','kpi'=>'Clasificación completa.','plazo'=>'Días 30–60','responsable'=>'Auditoría + Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'F-05','eje'=>'F','momento'=>'T2','accion'=>'Auditoría de las 40 estancias de Teniente Ochoa','justificacion'=>'208.000 hectáreas con contratos de 20 años próximos a vencer.','kpi'=>'Informe de auditoría. Modelo de renovación.','plazo'=>'Días 30–60','responsable'=>'Auditoría + Presidencia','estado'=>'BAJO REVISIÓN','detalle'=>'Más de 400.000 hectáreas totales del IPS en el Chaco.'],
            ['id'=>'F-06','eje'=>'F','momento'=>'T2','accion'=>'Revisión de contratos de favor (Hotel Guaraní y otros)','justificacion'=>'Contratos con renegociación a escondidas documentada.','kpi'=>'Contratos revisados. Ahorro por renegociación.','plazo'=>'Días 30–60','responsable'=>'Asesoría Jurídica + Gerencia Financiera','estado'=>'IDENTIFICADOS','detalle'=>'Incluye Manzana T, Hotel Acaray, Edificio Urundey, Edificio Yukyry, Fracción Isla de Francia.'],
            ['id'=>'F-07','eje'=>'F','momento'=>'T3','accion'=>'Aprobación del reglamento de enajenación','justificacion'=>'Contrastado y mejorado respecto a la propuesta de la Superintendencia.','kpi'=>'Reglamento aprobado por el Consejo.','plazo'=>'Días 75–100','responsable'=>'Consejo de Administración','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'F-08','eje'=>'F','momento'=>'T3','accion'=>'Plan de Monetización Estratégica de la cartera','justificacion'=>'Cartera mantenible, reactivable y enajenable.','kpi'=>'Plan aprobado con cronograma.','plazo'=>'Días 75–100','responsable'=>'Gerencia Financiera + Presidencia','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'F-09','eje'=>'F','momento'=>'T4','accion'=>'Revalorización de alquileres (de 2 a 5 de cada 10)','justificacion'=>'De cada 10 guaraníes que debería cobrar, apenas recibe 2.','kpi'=>'Ingreso por alquileres. Meta: triplicar.','plazo'=>'Meses 4–6 (meta intermedia)','responsable'=>'Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>'Ingreso actual: Gs. 400 millones mensuales para toda la cartera.'],
            ['id'=>'F-10','eje'=>'F','momento'=>'T4','accion'=>'Primeras operaciones de monetización','justificacion'=>'Generar ingresos por activos improductivos.','kpi'=>'N° de operaciones. Monto generado.','plazo'=>'Meses 4–6','responsable'=>'Gerencia Financiera + Jurídica','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'F-11','eje'=>'F','momento'=>'T4','accion'=>'Modificación especial del Art. 807 del Código Civil','justificacion'=>'Contratos de locación con inversión más allá de 20 años.','kpi'=>'Propuesta legislativa elevada.','plazo'=>'Meses 4–6','responsable'=>'Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-01','eje'=>'G','momento'=>'T0','accion'=>'Constatación del superávit del Fondo de Jubilaciones','justificacion'=>'El problema financiero está en Salud, no en Jubilaciones.','kpi'=>'Informe de constatación.','plazo'=>'24/04/2026','responsable'=>'Presidencia','estado'=>'EJECUTADO','detalle'=>'Confirmado al inicio de la gestión.'],
            ['id'=>'G-02','eje'=>'G','momento'=>'T1','accion'=>'Auditoría de jubilaciones graciosas','justificacion'=>'Viudas múltiples, beneficios irregulares.','kpi'=>'Informe de auditoría. Monto recuperable.','plazo'=>'Inicio Bloque 1; informe mes 3','responsable'=>'Auditoría Interna','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-03','eje'=>'G','momento'=>'T1','accion'=>'Auditoría del crecimiento anómalo de reposos','justificacion'=>'Reposos médicos con incremento sospechoso.','kpi'=>'Informe de auditoría.','plazo'=>'Inicio Bloque 1','responsable'=>'Auditoría Interna','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-04','eje'=>'G','momento'=>'T1','accion'=>'Auditoría de beneficios pagados a fallecidos','justificacion'=>'Pagos a derechohabientes fallecidos.','kpi'=>'N° de casos. Monto involucrado.','plazo'=>'Inicio Bloque 1','responsable'=>'Auditoría Interna','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-05','eje'=>'G','momento'=>'T1','accion'=>'Verificación del flujo financiero para pago de jubilaciones','justificacion'=>'Asegurar continuidad del pago.','kpi'=>'Flujo verificado para el resto del año.','plazo'=>'Día 30','responsable'=>'Gerencia Financiera + Tesorería','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-06','eje'=>'G','momento'=>'T2','accion'=>'Verificación y actualización de la Tasa Actuarial','justificacion'=>'Asegurar sustentabilidad del Fondo.','kpi'=>'Tasa actualizada adoptada.','plazo'=>'Días 45–60','responsable'=>'Gerencia Financiera + actuario','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-07','eje'=>'G','momento'=>'T2','accion'=>'Revisión del Art. 4° de la Ley 5.655/16 (40% rentas Fondo Jub.)','justificacion'=>'Impacto en la sustentabilidad del fondo.','kpi'=>'Propuesta legislativa elaborada.','plazo'=>'Días 45–60; propuesta en mes 7–9','responsable'=>'Asesoría Jurídica + Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>'Destino del 40% de las rentas del Fondo de Jubilaciones.'],
            ['id'=>'G-08','eje'=>'G','momento'=>'T4','accion'=>'Habilitación de nuevos instrumentos de inversión','justificacion'=>'Mejorar el rendimiento de las reservas.','kpi'=>'Nuevos instrumentos habilitados. Rendimiento mejorado.','plazo'=>'Meses 4–6','responsable'=>'Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-09','eje'=>'G','momento'=>'T4','accion'=>'Diálogo paramétrico con jubilados y asegurados','justificacion'=>'Construir legitimidad para eventuales reformas.','kpi'=>'Mesas de diálogo instaladas.','plazo'=>'Meses 4–6','responsable'=>'Presidencia + Comunicación','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-10','eje'=>'G','momento'=>'T4','accion'=>'Reforma del Reglamento de Caja de Préstamos','justificacion'=>'Préstamos a jubilados próximos a su fallecimiento.','kpi'=>'Reglamento reformado.','plazo'=>'Meses 4–6','responsable'=>'Asesoría Jurídica + Gerencia Financiera','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'G-11','eje'=>'G','momento'=>'T5','accion'=>'Propuesta de Superintendencia de Pensiones','justificacion'=>'Marco regulatorio para el sistema de pensiones.','kpi'=>'Propuesta elevada.','plazo'=>'Mes 9','responsable'=>'Presidencia + Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'H-01','eje'=>'H','momento'=>'T0','accion'=>'Acuerdo Cardiología Intervencionista/Hemodinamia','justificacion'=>'16 especialistas en conflicto por salarios y equipamiento.','kpi'=>'Acuerdo cumplido. Salarios ajustados.','plazo'=>'Cerrado 28/04; cumplimiento ≈ 28/07','responsable'=>'Presidencia + RRHH','estado'=>'CERRADO','detalle'=>'Compromiso de mejora salarial y reemplazo de angiógrafos.'],
            ['id'=>'H-02','eje'=>'H','momento'=>'T4','accion'=>'Ajuste salarial dirigido a especialistas críticos','justificacion'=>'Más de 1.500 médicos perciben Gs. 3.400.000 mensuales.','kpi'=>'N° de especialistas con ajuste. Monto promedio.','plazo'=>'Meses 4–6','responsable'=>'Presidencia + RRHH + Gerencia Financiera','estado'=>'COMPROMETIDO','detalle'=>'Hemodinamia y cardiología intervencionista entre Gs. 3.800.000 y Gs. 4.500.000.'],
            ['id'=>'H-03','eje'=>'H','momento'=>'T4','accion'=>'Evaluación de dotaciones por servicio','justificacion'=>'Crecimiento de 16.652 (2017) a 26.173 (2022) funcionarios, +57%.','kpi'=>'Dotaciones optimizadas por servicio.','plazo'=>'Meses 4–6','responsable'=>'RRHH + Planificación','estado'=>'PENDIENTE','detalle'=>'Crecimiento de asegurados en el mismo periodo: apenas 7%.'],
            ['id'=>'H-04','eje'=>'H','momento'=>'T4','accion'=>'Descontratación selectiva','justificacion'=>'Cerca del 48,6% del personal corresponde a contratados.','kpi'=>'N° de contratos no renovados.','plazo'=>'Meses 4–6 (gradual)','responsable'=>'RRHH + Asesoría Jurídica','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'H-05','eje'=>'H','momento'=>'T4','accion'=>'Plan de Salud Mental y respuesta a denuncias de SINADIPS','justificacion'=>'Denuncias de acoso laboral planteadas formalmente.','kpi'=>'Plan aprobado. Denuncias procesadas.','plazo'=>'Meses 4–6','responsable'=>'RRHH + Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'H-06','eje'=>'H','momento'=>'T4','accion'=>'Plan de formación continua y dignificación del médico','justificacion'=>'Formación y retención de talento médico.','kpi'=>'Plan piloto lanzado.','plazo'=>'Meses 4–6 para piloto; permanente','responsable'=>'Gerencia de Salud + RRHH','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'H-07','eje'=>'H','momento'=>'T4','accion'=>'Reforma del régimen del personal de atención al público','justificacion'=>'Mejorar la experiencia del asegurado.','kpi'=>'Reglamento reformado.','plazo'=>'Meses 4–6','responsable'=>'RRHH + Org. y Calidad','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'H-08','eje'=>'H','momento'=>'T4','accion'=>'Revisión del régimen de guardias activas y pasivas','justificacion'=>'Declaraciones sobre guardias pasivas generaron conflicto.','kpi'=>'Régimen revisado y aprobado.','plazo'=>'Meses 4–6','responsable'=>'RRHH + Gerencia de Salud','estado'=>'PENDIENTE','detalle'=>''],
            ['id'=>'I-01','eje'=>'I','momento'=>'T0','accion'=>'Visitas territoriales sin agenda previa','justificacion'=>'Diagnóstico de campo sin filtros. Impacto comunicacional.','kpi'=>'N° de visitas realizadas.','plazo'=>'Permanente','responsable'=>'Presidencia','estado'=>'EN CURSO','detalle'=>'Realizados: IPS Tebicuary, Hospital Regional de Encarnación, IPS Fram, Chaco.'],
            ['id'=>'I-02','eje'=>'I','momento'=>'T0','accion'=>'Conferencias con presencia de auditores del Ejecutivo','justificacion'=>'Señal de transparencia y de respaldo político.','kpi'=>'N° de conferencias con auditores presentes.','plazo'=>'Permanente','responsable'=>'Presidencia + Dirección de Prensa','estado'=>'EN CURSO','detalle'=>'Como ocurrió el 18/05 en el anuncio del vademécum.'],
            ['id'=>'I-03','eje'=>'I','momento'=>'T2','accion'=>'Creación de la Dirección de Prensa del IPS','justificacion'=>'En toda la historia del IPS nunca se creó una Dirección de Prensa.','kpi'=>'Dirección creada y operativa.','plazo'=>'Días 30–60','responsable'=>'Presidencia','estado'=>'EN ARMADO','detalle'=>'No tienen móvil, presupuesto ni tecnología según el Dr. Fretes.'],
            ['id'=>'I-04','eje'=>'I','momento'=>'T3','accion'=>'Encuesta institucional de satisfacción del asegurado','justificacion'=>'Línea de base medible para evaluación futura.','kpi'=>'Encuesta realizada. N° de respuestas.','plazo'=>'Días 75–100','responsable'=>'Org. y Calidad + Comunicación','estado'=>'PENDIENTE','detalle'=>'Metodología nacional.'],
            ['id'=>'I-05','eje'=>'I','momento'=>'TX','accion'=>'Diálogo permanente con UNJP, ANAIPS y gremios','justificacion'=>'Gestión de actores clave para la legitimidad.','kpi'=>'N° de reuniones. Reclamos canalizados.','plazo'=>'Permanente','responsable'=>'Presidencia + Dirección de Prensa','estado'=>'EN CURSO','detalle'=>'SINADIPS, ATIPS, Asociación Médica del IPS.'],
            ['id'=>'I-06','eje'=>'I','momento'=>'TX','accion'=>'Reuniones quincenales con el Presidente Peña','justificacion'=>'Sostener el respaldo del Poder Ejecutivo.','kpi'=>'Cadencia mantenida.','plazo'=>'Permanente','responsable'=>'Presidencia','estado'=>'EN CURSO','detalle'=>'Ejecutadas 29/04 y reafirmado 16/05.'],
            ['id'=>'I-07','eje'=>'I','momento'=>'T3','accion'=>'Estado Institucional de los 100 días','justificacion'=>'Balance verificable público.','kpi'=>'Presentación realizada. Cobertura mediática.','plazo'=>'Días 95–100','responsable'=>'Presidencia + Planificación','estado'=>'PENDIENTE','detalle'=>'Nuevo vademécum, canillas cerradas, stock recuperado, listas de espera.'],
            ['id'=>'I-08','eje'=>'I','momento'=>'T5','accion'=>'Informe de Gestión 9 meses','justificacion'=>'Cierre del ciclo con rendición de cuentas.','kpi'=>'Informe publicado.','plazo'=>'Enero 2027','responsable'=>'Presidencia + Planificación','estado'=>'PENDIENTE','detalle'=>''],
        ];
    }

    private function getDiagnosticos(): array
    {
        return [
            ['titulo'=>'Crisis financiera: la caja vacía cuantificada','contenido'=>'Déficit operativo mensual del Fondo de Salud entre USD 20 y USD 23 millones. Pasivo total cercano a USD 1.050 millones. Deuda con proveedores en torno a USD 300-350 millones. Deuda histórica del Estado estimada entre USD 600-640 millones. Facturas contabilizadas por USD 320 millones, prácticamente el doble del presupuesto disponible bajo escenario pesimista. PAC programado 2026 por USD 500 millones. El Fondo de Jubilaciones presenta superávit.','tags'=>['finanzas','déficit','deuda','pasivo','USD','caja vacía']],
            ['titulo'=>'Crisis operativa: medicamentos e insumos','contenido'=>'Vademécum con 4.000 ítems. Más de 150 medicamentos en stock cero. 988 productos identificados como adquisiciones sin justificación técnica. 25.000 bolsas pediátricas para 10 pacientes. Enzalutamida: compra para 24 meses consumida en 6. Trasplantados renales sin Micofenolato Sódico, Tacrolimus, Ciclosporina, Sirolimus. Licitación de G. 76.000 millones sin justificación médica mientras 164 asegurados pueden perder la vista.','tags'=>['medicamentos','vademécum','stock cero','enzalutamida','bolsas pediátricas','trasplantes']],
            ['titulo'=>'Listas de espera y modelo de atención','contenido'=>'Más de 7.000 asegurados aguardando consulta de endocrinología. Tiempos de espera para cirugías: 3-6 meses. Turnos de hasta 8 meses para atención especializada. Angiógrafos con 10 años de antigüedad, sin repuestos. Solo 2 equipos de hemodinamia para 2 millones de beneficiarios (estándar: 1 cada 400.000). Hospital INGAVI sin completar operatividad desde marzo 2017. Hospital 12 de Junio con baños clausurados.','tags'=>['lista de espera','endocrinología','angiografía','hemodinamia','INGAVI','turnos']],
            ['titulo'=>'Crisis de gobernanza y control interno','contenido'=>'De 29 observaciones de la CGR, el IPS aprobó apenas 4. Sistema antiincendio de G. 9.000 millones que no se activó en el incendio del 18/02/2026. Licitación de ascensores adjudicada tres veces a la oferta más cara. Contratos de servicios: G. 45.000M jardinería, G. 160.000M higienización, G. 270.000M biohigiene. Total: G. 475.000 millones (≈ USD 65 millones). Vulnerabilidades informáticas que permiten borrar deudas patronales.','tags'=>['gobernanza','CGR','antiincendio','ascensores','contratos','AOP','control interno']],
            ['titulo'=>'Crisis del patrimonio inmobiliario','contenido'=>'Cartera de 776 a más de 800 propiedades. 208.000 hectáreas en Teniente Ochoa con concesiones próximas a vencer. Más de 400.000 hectáreas totales en el Chaco. Rentabilidad de 0,04% anual. Ingreso por alquileres: Gs. 400 millones mensuales. De cada 10 guaraníes que debería cobrar, apenas recibe 2. Inmuebles emblemáticos: Manzana T, Hotel Guaraní, Hotel Acaray, Edificio Urundey, Edificio Yukyry.','tags'=>['inmobiliario','propiedades','Chaco','Teniente Ochoa','alquileres','Hotel Guaraní','patrimonio']],
            ['titulo'=>'Crisis de recursos humanos','contenido'=>'Personal: 16.652 (2017) a 26.173 (2022), +57%. Asegurados: +7% en el mismo periodo. 48,6% del personal es contratado. Gasto en personal pasó de Gs. 1.338.788M a Gs. 2.197.874M (+64%). Más de 1.500 médicos perciben Gs. 3.400.000 mensuales. Especialistas en hemodinamia: Gs. 3.800.000-4.500.000. Denuncias de acoso laboral (SINADIPS). Nunca se creó una Dirección de Prensa.','tags'=>['recursos humanos','personal','salarios','contratados','SINADIPS','médicos']],
            ['titulo'=>'Crisis de legitimidad','contenido'=>'ANAIPS exigiendo declaración de emergencia sanitaria. UNJP movilizada exigiendo barrida del Consejo. Senadora Yolanda Paredes reclamando denuncias formales ante la Fiscalía. Narrativa instalada: "IPS es una estafa", "IPS debería ser OPTATIVO". Fuga voluntaria de asegurados a la informalidad. Editorial de La Nación: "Fretes rompe el molde en el IPS". El presidente Peña ratificó su apoyo el 13 de mayo.','tags'=>['legitimidad','ANAIPS','UNJP','crisis','optativo','confianza']],
        ];
    }

    private function getCanillas(): array
    {
        return [
            ['tipo'=>'Compras innecesarias en el vademécum','ejemplos'=>'988 ítems sin uso médico vigente. Bolsas pediátricas (25.000 para 10 pacientes). Enzalutamida (24 meses consumidos en 6).','estrategia'=>'Saneamiento del vademécum con jefes médicos. Cuadro básico depurado. Nueva gobernanza de compras.','monto'=>'Múltiples licitaciones anuales, cada una entre G. 25.000M y G. 76.000M'],
            ['tipo'=>'Servicios tercerizados sobredimensionados','ejemplos'=>'Jardinería (G. 45.000M). Higienización/textiles (G. 160.000M). Biohigiene (G. 270.000M).','estrategia'=>'Revisión contractual. Suspensión, renegociación o cancelación. Verificación de precios y prestación efectiva.','monto'=>'Total ≈ G. 475.000 millones (≈ USD 65 millones) anuales'],
            ['tipo'=>'Contratos con ejecución cero o irregular','ejemplos'=>'Prótesis traumatológicas con ejecución 0%. Sistema antiincendio G. 9.000M que no se activó. Quirófanos modulares no entregados.','estrategia'=>'Auditoría puntual. Reactivación, rescisión o ejecución de garantías. Inhabilitación a empresas incumplidoras.','monto'=>'Variable por contrato'],
            ['tipo'=>'Licitaciones con sobreprecio o direccionamiento','ejemplos'=>'Ascensores (oferta más cara ganadora tres veces). Licitación pendiente de G. 76.000M a desactivar.','estrategia'=>'Revisión de pliegos. Desactivación o anulación. Denuncia formal ante DNCP, Fiscalía y Contraloría.','monto'=>'Variable'],
            ['tipo'=>'Tercerizaciones con control flojo','ejemplos'=>'Caaguazú y Campo 9 (un solo fiscalizador para miles de procedimientos). Chaco (costos elevados). Imagenología con estudios no retirados.','estrategia'=>'Auditoría inmediata. Refuerzo de fiscalización con tecnología. Migración a capacidad propia cuando convenga.','monto'=>'Variable; alto riesgo de fugas'],
            ['tipo'=>'Inmuebles improductivos','ejemplos'=>'776+ propiedades con rentabilidad 0,04% anual. Solo se cobra 2 de cada 10 guaraníes. 40 estancias con concesiones próximas a vencer.','estrategia'=>'Auditoría patrimonial interinstitucional. Reglamento de enajenación. Renegociación de contratos de favor.','monto'=>'Potencial de triplicar ingresos por arrendamientos'],
            ['tipo'=>'Vulnerabilidades en AOP y borrado de deudas','ejemplos'=>'Sistema informático que permite borrar deudas patronales y agregar antigüedad fraudulenta.','estrategia'=>'Auditoría informática. Inhabilitación técnica. Rotación de fiscalizadores. Coordinación con SET y MTESS.','monto'=>'Incuantificable directamente; impacto en recaudación'],
            ['tipo'=>'Beneficios económicos irregulares','ejemplos'=>'Jubilaciones "graciosas". Crecimiento anómalo de reposos. Beneficios pagados a fallecidos. Préstamos a jubilados próximos a su fallecimiento.','estrategia'=>'Auditoría puntual. Inteligencia de datos. Refuerzo del fondo solidario de garantía.','monto'=>'Variable'],
            ['tipo'=>'Combustibles y gastos administrativos','ejemplos'=>'Gs. 7.200 millones en 2022 para 133 vehículos con 40% fuera de servicio. Viajes en avión injustificados.','estrategia'=>'Auditoría puntual. Sistema de control de uso. Trazabilidad por GPS y centralización de flota.','monto'=>'Gs. 7.200 millones anuales (solo combustible)'],
            ['tipo'=>'Compras infladas en cantidades','ejemplos'=>'Compras por 24 meses consumidas en 6. Productos con vida útil que vencen sin uso.','estrategia'=>'Programación basada en consumo histórico real. Aprobación técnica obligatoria de cantidades. Sistema de alerta de vencimientos.','monto'=>'Un 10% de ahorro en este concepto representa decenas de millones de dólares anuales'],
        ];
    }

    private function getCitas(): array
    {
        return [
            ['texto'=>'Vengo por patriotismo; no sé robar.','autor'=>'Dr. Isaías Fretes','fecha'=>'22 de abril de 2026','contexto'=>'Declaración al asumir la presidencia del IPS.'],
            ['texto'=>'Esta institución tiene un déficit de 23 millones de dólares al mes.','autor'=>'Dr. Isaías Fretes','fecha'=>'16 de mayo de 2026','contexto'=>'Entrevista en 1020 AM.'],
            ['texto'=>'Yo sé que la gente es muy ansiosa, que quiere todos los cambios inmediatamente, pero resulta que hace tres semanas que estoy en la institución y sin plata.','autor'=>'Dr. Isaías Fretes','fecha'=>'16 de mayo de 2026','contexto'=>'Entrevista en 1020 AM.'],
            ['texto'=>'El doctor Isaías Fretes le ha puesto una impronta que creo que es única, nunca antes habíamos visto. Está haciendo un trabajo muy importante, y obviamente en el IPS todo es una urgencia, todo es una emergencia, estamos tratando de cubrir déficits que tienen décadas.','autor'=>'Presidente Santiago Peña','fecha'=>'13 de mayo de 2026','contexto'=>'Ratificación pública del respaldo a la conducción.'],
            ['texto'=>'A muchos no les va a caer muy bien mi sincericidio, pero si uno no asume la verdad, no se van a poder hacer los cambios.','autor'=>'Dr. Isaías Fretes','fecha'=>'Mayo 2026','contexto'=>'Sobre su estilo de comunicación directa.'],
            ['texto'=>'La situación no está fácil. También con focos de corrupción que se arrastran desde hace tiempo. El objetivo es llegar a la parte final del camino que va a culminar con el cambio de la Carta Magna, pero mientras tanto tenemos que cerrar las canillas de fuga.','autor'=>'Dr. Isaías Fretes','fecha'=>'18 de mayo de 2026','contexto'=>'Conferencia de prensa sobre el vademécum.'],
            ['texto'=>'Una de las formas de cerrar esas canillas que están goteando es trabajar sobre los puntos más álgidos, y el cambio del vademécum es entrar al corazón de la corrupción.','autor'=>'Dr. Isaías Fretes','fecha'=>'18 de mayo de 2026','contexto'=>'Anuncio del retiro de 988 ítems.'],
            ['texto'=>'De cada 10 guaraníes que el IPS debería cobrar por el alquiler de sus propiedades, apenas recibe 2 guaraníes.','autor'=>'Dr. Isaías Fretes','fecha'=>'18 de mayo de 2026','contexto'=>'Anuncio de la auditoría patrimonial.'],
            ['texto'=>'Tengo que desactivar una licitación de G. 76.000 millones que no tiene sentido. Y, en contrapartida, tengo 164 asegurados que van a perder la vista.','autor'=>'Dr. Isaías Fretes','fecha'=>'18 de mayo de 2026','contexto'=>'Conferencia sobre vademécum y prioridades.'],
            ['texto'=>'El antibiótico que era válido diez años atrás, hoy ya no se usa. La misma cosa ocurre con los insumos.','autor'=>'Dr. Isaías Fretes','fecha'=>'13 de mayo de 2026','contexto'=>'Taller de revisión del vademécum.'],
            ['texto'=>'Hay resultados de resonancias y tomografías que el paciente nunca retiró. Quiere decir que el paciente está bien y se pidió al pedo.','autor'=>'Dr. Isaías Fretes','fecha'=>'29 de abril de 2026','contexto'=>'Sobre la tercerización de estudios médicos.'],
            ['texto'=>'Estamos permitiendo o dando oportunidad a fugas.','autor'=>'Consejo del IPS','fecha'=>'12 de mayo de 2026','contexto'=>'Sobre el control de tercerizaciones en Caaguazú y Campo 9.'],
            ['texto'=>'IPS es rico... no darle parches.','autor'=>'Dr. Isaías Fretes','fecha'=>'Mayo 2026','contexto'=>'Línea editorial de la comunicación institucional.'],
            ['texto'=>'Estamos ante un hombre honesto. Y en el ecosistema del IPS, esa no es una cualidad menor; es casi una rareza.','autor'=>'Editorial La Nación','fecha'=>'3 de mayo de 2026','contexto'=>'Editorial "Fretes rompe el molde en el IPS".'],
            ['texto'=>'La perla del sur aquí no tiene nada de perla.','autor'=>'Dr. Isaías Fretes','fecha'=>'9 de mayo de 2026','contexto'=>'Visita territorial a Encarnación.'],
            ['texto'=>'Habiendo vidas de por medio, ¿ndoikói?','autor'=>'Dr. Isaías Fretes','fecha'=>'Mayo 2026','contexto'=>'Sobre el sistema antiincendio del Hospital Central.'],
        ];
    }
}
