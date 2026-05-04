<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Admin\Globales\Organigrama;

/**
 * Crea el organigrama completo del IPS usando appendNode()
 * para que NestedSet calcule _lft/_rgt correctamente.
 *
 * Estructura:
 * INSTITUTO DE PREVISIÓN SOCIAL (id=8, ya existe)
 *   └── CONSEJO DE ADMINISTRACIÓN
 *         └── PRESIDENCIA
 *               ├── GERENCIA DE DESARROLLO Y TECNOLOGÍA
 *               │     └── DIRECCIÓN DE TIC
 *               ├── GERENCIA GENERAL DE SALUD
 *               │     ├── DIRECCIÓN DE HOSPITALES ÁREA CENTRAL
 *               │     ├── DIRECCIÓN DE HOSPITALES INTERIOR
 *               │     └── DIRECCIÓN DE REGULACIÓN MÉDICA
 *               ├── GERENCIA DE ADMINISTRACIÓN Y FINANZAS
 *               │     ├── DIRECCIÓN DE CONTABILIDAD Y PRESUPUESTO
 *               │     ├── DIRECCIÓN DE TESORERÍA
 *               │     └── DIRECCIÓN DE INVERSIONES
 *               ├── GERENCIA DE RECURSOS HUMANOS
 *               │     └── DIRECCIÓN GESTIÓN Y DESARROLLO DEL TALENTO HUMANO
 *               ├── DIRECCIÓN DE PLANIFICACIÓN
 *               │     └── DEPARTAMENTO DE ESTADÍSTICAS (SIESS)
 *               └── DIRECCIÓN DE ASESORÍA JURÍDICA
 */
class OrganigramaIpsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creando organigrama IPS...');

        // Verificar si ya existe para no duplicar
        if (Organigrama::where('dependency', 'INSTITUTO DE PREVISIÓN SOCIAL')
            ->whereNull('parent_id')
            ->where('id', '!=', 8) // excluir el de test
            ->exists()) {
            $this->command->warn('El organigrama IPS real ya fue creado. Abortando para evitar duplicados.');
            return;
        }

        // ── Nodo raíz nuevo e independiente ──────────────────────────────────
        $raiz = Organigrama::create([
            'dependency' => 'INSTITUTO DE PREVISIÓN SOCIAL',
            'manager'    => 'Consejo de Administración',
            'email'      => 'ips.root@ips.gov.py',
            'phone'      => '000000',
        ]);
        $this->command->line("  ✓ INSTITUTO DE PREVISIÓN SOCIAL (raíz, id={$raiz->id})");

        // ── Nivel 1: Consejo de Administración ───────────────────────────────
        $ca = $this->crear($raiz, 'CONSEJO DE ADMINISTRACIÓN', 'Presidente CA', 'ca@ips.gov.py');

        // ── Nivel 2: Presidencia ──────────────────────────────────────────────
        $presidencia = $this->crear($ca, 'PRESIDENCIA', 'Dr. Vicente Mario Bataglia Araújo', 'presidencia@ips.gov.py');

        // ── Nivel 3: Gerencias y Direcciones directas ─────────────────────────

        $gerDesarrollo = $this->crear($presidencia, 'GERENCIA DE DESARROLLO Y TECNOLOGÍA (A)', 'Gerente de Desarrollo', 'desarrollo@ips.gov.py');
        $gerSalud      = $this->crear($presidencia, 'GERENCIA GENERAL DE SALUD (M)',            'Gerente General de Salud', 'salud@ips.gov.py');
        $gerAdmin      = $this->crear($presidencia, 'GERENCIA DE ADMINISTRACIÓN Y FINANZAS (A)','Gerente Admin y Finanzas', 'admin@ips.gov.py');
        $gerRrhh       = $this->crear($presidencia, 'GERENCIA DE RECURSOS HUMANOS (A)',          'Gerente de RRHH', 'rrhh@ips.gov.py');
        $dirPlan       = $this->crear($presidencia, 'DIRECCIÓN DE PLANIFICACIÓN (E)',            'Director de Planificación', 'planificacion@ips.gov.py');
        $dirJuridica   = $this->crear($presidencia, 'DIRECCIÓN DE ASESORÍA JURÍDICA (A)',        'Asesor Jurídico', 'juridica@ips.gov.py');

        // ── Nivel 4: Direcciones bajo cada Gerencia ───────────────────────────

        // Bajo Gerencia de Desarrollo y Tecnología
        $this->crear($gerDesarrollo, 'DIRECCIÓN DE TECNOLOGÍA DE LA INFORMACIÓN Y COMUNICACIONES', 'Director de TIC', 'tic@ips.gov.py');

        // Bajo Gerencia General de Salud
        $this->crear($gerSalud, 'DIRECCIÓN DE HOSPITALES ÁREA CENTRAL', 'Director Hosp. Área Central', 'hospcentral@ips.gov.py');
        $this->crear($gerSalud, 'DIRECCIÓN DE HOSPITALES INTERIOR',     'Director Hosp. Interior',     'hospinterior@ips.gov.py');
        $this->crear($gerSalud, 'DIRECCIÓN DE REGULACIÓN MÉDICA',       'Director Reg. Médica',        'regmedica@ips.gov.py');

        // Bajo Gerencia de Administración y Finanzas
        $this->crear($gerAdmin, 'DIRECCIÓN DE CONTABILIDAD Y PRESUPUESTO', 'Director Contabilidad', 'contabilidad@ips.gov.py');
        $this->crear($gerAdmin, 'DIRECCIÓN DE TESORERÍA',                  'Director Tesorería',    'tesoreria@ips.gov.py');
        $this->crear($gerAdmin, 'DIRECCIÓN DE INVERSIONES',                'Director Inversiones',  'inversiones@ips.gov.py');

        // Bajo Gerencia de Recursos Humanos
        $this->crear($gerRrhh, 'DIRECCIÓN GESTIÓN Y DESARROLLO DEL TALENTO HUMANO', 'Director Talento Humano', 'talentohumano@ips.gov.py');

        // Bajo Dirección de Planificación
        $this->crear($dirPlan, 'DEPARTAMENTO DE ESTADÍSTICAS (SIESS)', 'Jefe de Estadísticas', 'estadisticas@ips.gov.py');

        $this->command->info('✅ Organigrama IPS creado correctamente.');

        // Mostrar resumen
        $total = Organigrama::whereDescendantOf($raiz)->count();
        $this->command->table(
            ['Nodo raíz', 'ID', 'Descendientes creados'],
            [['INSTITUTO DE PREVISIÓN SOCIAL', $raiz->id, $total]]
        );
    }

    private function crear(Organigrama $padre, string $nombre, string $manager, string $email): Organigrama
    {
        // Garantizar email único agregando timestamp si ya existe
        if (Organigrama::where('email', $email)->exists()) {
            $email = str_replace('@', '.new@', $email);
        }

        $nodo = new Organigrama([
            'dependency' => $nombre,
            'manager'    => $manager,
            'email'      => $email,
            'phone'      => '000000',
        ]);
        $padre->appendNode($nodo);
        $this->command->line("  ✓ {$nombre}");
        return $nodo;
    }
}
