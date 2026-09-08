<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Eventos\Evento;
use App\Models\Eventos\EventoPaso;
use App\Models\Eventos\EventoTarea;
use App\Admin\Planificacion\Pei\PeiProfile;
use App\Models\User;

class EventoDemoSocializacionPeiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pei = PeiProfile::where('id', 'ce99f883-fdd0-4723-8f75-cf689aa8f0fa')->first()
            ?? PeiProfile::first();

        $adminUser = User::first();
        $users = User::take(5)->get();

        $u1 = $users->get(0) ?? $adminUser;
        $u2 = $users->get(1) ?? $adminUser;
        $u3 = $users->get(2) ?? $adminUser;

        // Limpiar evento anterior si ya existiera con el mismo nombre
        $eventoExistente = Evento::where('nombre', 'Taller de Socialización del Plan Estratégico Institucional 2026–2028')->first();
        if ($eventoExistente) {
            $eventoExistente->forceDelete();
        }

        // 1. Crear Evento Principal
        $evento = Evento::create([
            'id' => (string) Str::uuid(),
            'pei_profile_id' => $pei ? $pei->id : null,
            'nombre' => 'Taller de Socialización del Plan Estratégico Institucional 2026–2028',
            'tipo' => 'Taller',
            'descripcion' => 'Jornada integral de difusión, alineación estratégica y adopción de los 3 Objetivos Estratégicos reestructurados con la Alta Gerencia y dependencias clave del IPS.',
            'fecha_inicio' => '2026-09-01',
            'fecha_fin' => '2026-09-20',
            'lugar_sede' => 'Centro de Convenciones Ykua Satí & Edificio Central IPS',
            'color' => '#4f46e5',
            'estado' => 'en_curso',
            'presupuesto_estimado' => 25000000,
            'presupuesto_ejecutado' => 8500000,
            'created_by' => $adminUser ? $adminUser->id : null,
        ]);

        if ($u1) {
            $evento->responsables()->attach($u1->id, ['rol_evento' => 'Coordinador General']);
        }
        if ($u2) {
            $evento->responsables()->attach($u2->id, ['rol_evento' => 'Apoyo Logístico']);
        }

        // 2. PASO 1: Aprobación / Actualización desde el MEF (01 al 10 de Setiembre)
        $paso1 = EventoPaso::create([
            'id' => (string) Str::uuid(),
            'evento_id' => $evento->id,
            'orden' => 1,
            'nombre' => 'Paso 1: Aprobación y Actualización del Plan desde el MEF',
            'descripcion' => 'Gestión del dictamen técnico favorable y compatibilización con lineamientos metodológicos del Ministerio de Economía y Finanzas.',
            'fecha_inicio' => '2026-09-01',
            'fecha_fin' => '2026-09-10',
            'estado' => 'en_proceso',
            'color' => '#3b82f6',
            'responsable_principal_id' => $u1 ? $u1->id : null,
            'created_by' => $adminUser ? $adminUser->id : null,
        ]);

        // Tareas Paso 1
        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso1->id,
            'evento_id' => $evento->id,
            'nombre' => 'Elaborar informe técnico de compatibilización con lineamientos MEF',
            'descripcion' => 'Ajustar redacción de los 3 O.E. según taxonomía MEF vigente.',
            'responsable_id' => $u1 ? $u1->id : null,
            'fecha_limite' => '2026-09-03',
            'prioridad' => 'alta',
            'completada' => true,
            'completada_el' => '2026-09-03 14:30:00',
            'completada_por' => $u1 ? $u1->id : null,
            'costo_estimado' => 0,
            'orden' => 1,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso1->id,
            'evento_id' => $evento->id,
            'nombre' => 'Remitir nota oficial de solicitud de dictamen al MEF',
            'descripcion' => 'Firma de Presidencia / Dirección de Planificación y mesa de entrada oficial.',
            'responsable_id' => $u1 ? $u1->id : null,
            'fecha_limite' => '2026-09-06',
            'prioridad' => 'urgente',
            'completada' => true,
            'completada_el' => '2026-09-06 10:15:00',
            'completada_por' => $u1 ? $u1->id : null,
            'costo_estimado' => 0,
            'orden' => 2,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso1->id,
            'evento_id' => $evento->id,
            'nombre' => 'Seguimiento diario y recepción del dictamen favorable MEF',
            'descripcion' => 'Reunión de enlace con analistas de la Dirección General de Planificación del MEF.',
            'responsable_id' => $u2 ? $u2->id : null,
            'fecha_limite' => '2026-09-10',
            'prioridad' => 'alta',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 3,
        ]);

        // 3. PASO 2: Organizar Reunión con Alta Gerencia (09 al 15 de Setiembre)
        $paso2 = EventoPaso::create([
            'id' => (string) Str::uuid(),
            'evento_id' => $evento->id,
            'orden' => 2,
            'nombre' => 'Paso 2: Organizar Reunión con Alta Gerencia',
            'descripcion' => 'Convocatoria y preparación de insumos estratégicos para Gerentes y Directores Generales del IPS.',
            'fecha_inicio' => '2026-09-09',
            'fecha_fin' => '2026-09-15',
            'estado' => 'en_proceso',
            'color' => '#8b5cf6',
            'responsable_principal_id' => $u2 ? $u2->id : null,
            'created_by' => $adminUser ? $adminUser->id : null,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso2->id,
            'evento_id' => $evento->id,
            'nombre' => 'Preparar presentación ejecutiva de los 3 Objetivos Estratégicos',
            'descripcion' => 'Diseño de diapositivas con metas, indicadores y presupuesto plurianual.',
            'responsable_id' => $u2 ? $u2->id : null,
            'fecha_limite' => '2026-09-11',
            'prioridad' => 'alta',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 1,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso2->id,
            'evento_id' => $evento->id,
            'nombre' => 'Enviar invitaciones formales y notas circulares a Directores y Gerentes',
            'descripcion' => 'Remisión vía sistema documental y WhatsApp institucional a miembros de la Gerencia.',
            'responsable_id' => $u3 ? $u3->id : null,
            'fecha_limite' => '2026-09-12',
            'prioridad' => 'media',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 2,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso2->id,
            'evento_id' => $evento->id,
            'nombre' => 'Llamar y confirmar asistencia individual de Gerentes',
            'descripcion' => 'Reconfirmación telefónica para garantizar quorum institucional.',
            'responsable_id' => $u3 ? $u3->id : null,
            'fecha_limite' => '2026-09-14',
            'prioridad' => 'alta',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 3,
        ]);

        // 4. PASO 3: Jornada en Ykua Satí (16 al 20 de Setiembre)
        $paso3 = EventoPaso::create([
            'id' => (string) Str::uuid(),
            'evento_id' => $evento->id,
            'orden' => 3,
            'nombre' => 'Paso 3: Jornada en Ykua Satí & Firma de Compromisos',
            'descripcion' => 'Ejecución presencial del taller de socialización, mesas de trabajo y suscripción del compromiso del Plan PEI.',
            'fecha_inicio' => '2026-09-16',
            'fecha_fin' => '2026-09-20',
            'estado' => 'pendiente',
            'color' => '#10b981',
            'responsable_principal_id' => $u3 ? $u3->id : null,
            'created_by' => $adminUser ? $adminUser->id : null,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso3->id,
            'evento_id' => $evento->id,
            'nombre' => 'Coordinar salón, equipamiento audiovisual y sonido en Ykua Satí',
            'descripcion' => 'Verificación técnica in situ de micrófonos, pantallas y climatización.',
            'responsable_id' => $u2 ? $u2->id : null,
            'fecha_limite' => '2026-09-16',
            'prioridad' => 'urgente',
            'completada' => false,
            'costo_estimado' => 5000000,
            'orden' => 1,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso3->id,
            'evento_id' => $evento->id,
            'nombre' => 'Imprimir carpetas de trabajo, cronogramas y fichas de indicadores',
            'descripcion' => '50 carpetas institucionales con el resumen del PEI y matrices de metas.',
            'responsable_id' => $u3 ? $u3->id : null,
            'fecha_limite' => '2026-09-15',
            'prioridad' => 'media',
            'completada' => false,
            'costo_estimado' => 1500000,
            'orden' => 2,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso3->id,
            'evento_id' => $evento->id,
            'nombre' => 'Contratación y supervisión del servicio de Coffee Break / Almuerzo',
            'descripcion' => 'Recepción de los servicios de catering para las jornadas del 16 al 18.',
            'responsable_id' => $u3 ? $u3->id : null,
            'fecha_limite' => '2026-09-16',
            'prioridad' => 'media',
            'completada' => false,
            'costo_estimado' => 12000000,
            'orden' => 3,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso3->id,
            'evento_id' => $evento->id,
            'nombre' => 'Ejecución del Taller, dinámicas grupales y firma de actas',
            'descripcion' => 'Facilitación de las mesas de trabajo y consolidación de acuerdos institucionales.',
            'responsable_id' => $u1 ? $u1->id : null,
            'fecha_limite' => '2026-09-18',
            'prioridad' => 'urgente',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 4,
        ]);

        EventoTarea::create([
            'id' => (string) Str::uuid(),
            'evento_paso_id' => $paso3->id,
            'evento_id' => $evento->id,
            'nombre' => 'Elaboración y difusión de las Memorias Finales del Taller',
            'descripcion' => 'Compilación del documento de relatoría y remisión a Presidencia y Consejo de Administración.',
            'responsable_id' => $u1 ? $u1->id : null,
            'fecha_limite' => '2026-09-20',
            'prioridad' => 'alta',
            'completada' => false,
            'costo_estimado' => 0,
            'orden' => 5,
        ]);

        $this->command->info('Seeder de Evento Demo ejecutado exitosamente con 3 Pasos y 11 Tareas.');
    }
}
