<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Admin\Globales\Organigrama;

class OrganigramaIpsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creando organigrama institucional IPS 2026...');

        $raiz = Organigrama::where('dependency', 'INSTITUTO DE PREVISIÓN SOCIAL')
            ->whereNull('parent_id')
            ->first();

        if (!$raiz) {
            $raiz = Organigrama::create([
                'dependency' => 'INSTITUTO DE PREVISIÓN SOCIAL',
                'manager'    => 'Consejo de Administración',
                'email'      => 'ips.root@ips.gov.py',
                'phone'      => '000000',
            ]);
        }

        $this->command->line("  ✓ Usando raíz de organigrama IPS (id={$raiz->id})");

        $ca = $this->crear($raiz, 'CONSEJO DE ADMINISTRACIÓN', 'Presidente del Consejo', 'consejo.administracion@ips.gov.py');
        $presidencia = $this->crear($ca, 'PRESIDENCIA', 'Presidente Ejecutivo', 'presidencia@ips.gov.py');
        $secretaria = $this->crear($presidencia, 'SECRETARÍA GENERAL', 'Secretaria General', 'secretaria.general@ips.gov.py');
        $juridica = $this->crear($presidencia, 'GERENCIA DE ASESORÍA JURÍDICA', 'Gerente Jurídico', 'asesoria.juridica@ips.gov.py');
        $desarrollo = $this->crear($presidencia, 'GERENCIA DE DESARROLLO Y TECNOLOGÍA', 'Gerente de Desarrollo y Tecnología', 'desarrollo.tecnologia@ips.gov.py');
        $salud = $this->crear($presidencia, 'GERENCIA GENERAL DE SALUD', 'Gerente General de Salud', 'salud@ips.gov.py');
        $finanzas = $this->crear($presidencia, 'GERENCIA DE ADMINISTRACIÓN Y FINANZAS', 'Gerente de Administración y Finanzas', 'administracion.finanzas@ips.gov.py');
        $participacion = $this->crear($presidencia, 'GERENCIA DE PARTICIPACIÓN ECONÓMICA DEL SOC', 'Gerente de Participación Económica', 'participacion.economica@ips.gov.py');

        $this->crear($desarrollo, 'DIRECCIÓN DE TECNOLOGÍA DE LA INFORMACIÓN Y COMUNICACIONES', 'Director TIC', 'tic@ips.gov.py');

        $this->crear($salud, 'DIRECCIÓN DE HOSPITALES ÁREA CENTRAL', 'Director Área Central', 'hosp.central@ips.gov.py');
        $this->crear($salud, 'DIRECCIÓN DE HOSPITALES INTERIOR', 'Director Interior', 'hosp.interior@ips.gov.py');
        $this->crear($salud, 'DIRECCIÓN DE REGULACIÓN MÉDICA', 'Director Regulación Médica', 'regulacion.medica@ips.gov.py');

        $this->crear($finanzas, 'DIRECCIÓN DE CONTABILIDAD Y PRESUPUESTO', 'Director de Contabilidad', 'contabilidad@ips.gov.py');
        $this->crear($finanzas, 'DIRECCIÓN DE TESORERÍA', 'Director de Tesorería', 'tesoreria@ips.gov.py');
        $this->crear($finanzas, 'DIRECCIÓN DE INVERSIONES', 'Director de Inversiones', 'inversiones@ips.gov.py');

        $this->crear($participacion, 'DIRECCIÓN DE PARTICIPACIÓN Y DESARROLLO SOCIAL', 'Director de Participación', 'participacion.social@ips.gov.py');
        $this->crear($participacion, 'DIRECCIÓN DE PROYECTOS ECONÓMICOS SOCIALES', 'Director de Proyectos', 'proyectos.economicos@ips.gov.py');

        $this->command->info('✅ Organigrama IPS 2026 creado correctamente.');
        $total = Organigrama::whereDescendantOf($raiz)->count();
        $this->command->table(
            ['Nodo raíz', 'ID', 'Descendientes creados'],
            [['INSTITUTO DE PREVISIÓN SOCIAL', $raiz->id, $total]]
        );
    }

    private function crear(Organigrama $padre, string $nombre, string $manager, string $email): Organigrama
    {
        $existente = Organigrama::where('dependency', $nombre)
            ->where('parent_id', $padre->id)
            ->first();

        if ($existente) {
            $this->command->line("  • {$nombre} (ya existía)");
            return $existente;
        }

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
