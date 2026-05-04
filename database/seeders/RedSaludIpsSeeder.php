<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Admin\Globales\Organigrama;

/**
 * Crea los establecimientos de la Red de Salud IPS como nodos del organigrama.
 * Dependen de:
 *   - DIRECCIÓN DE HOSPITALES ÁREA CENTRAL (id=37) → Asunción y Central
 *   - DIRECCIÓN DE HOSPITALES INTERIOR (id=38)     → resto del país
 */
class RedSaludIpsSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Creando Red de Salud IPS...');

        $dirCentral  = Organigrama::find(37);
        $dirInterior = Organigrama::find(38);

        if (!$dirCentral || !$dirInterior) {
            $this->command->error('No se encontraron las Direcciones de Hospitales (id=37 y 38). Ejecutá primero OrganigramaIpsSeeder.');
            return;
        }

        // Verificar si ya existe
        if (Organigrama::whereDescendantOf($dirCentral)->whereNotNull('tipo_establecimiento')->exists()) {
            $this->command->warn('Los establecimientos ya fueron creados. Abortando.');
            return;
        }

        $creados = 0;

        // ── ASUNCIÓN Y CENTRAL → Dirección de Hospitales Área Central ────────
        $establecimientos = [
            // ASUNCIÓN
            ['dependency'=>'Hospital de Clínicas',          'tipo'=>'H.',     'nivel'=>'N4', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital de Clínicas'],
            ['dependency'=>'Hospital Geriátrico',           'tipo'=>'H.',     'nivel'=>'N3', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Geriátrico'],
            ['dependency'=>'Sanatorio IPS',                 'tipo'=>'H.',     'nivel'=>'N3', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Sanatorio IPS'],
            ['dependency'=>'Centro Materno Infantil',       'tipo'=>'C.M.I.', 'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director CMI'],
            ['dependency'=>'Centro de Salud Barrio Obrero', 'tipo'=>'C.S.',   'nivel'=>'N1', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Jefe C.S. Barrio Obrero'],
            ['dependency'=>'P.S. Republicano',              'tipo'=>'P.S.',   'nivel'=>'N1', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Jefe P.S. Republicano'],
            // CENTRAL
            ['dependency'=>'Hospital de Luque',             'tipo'=>'H.',     'nivel'=>'N3', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Luque'],
            ['dependency'=>'Hospital de San Lorenzo',       'tipo'=>'H.',     'nivel'=>'N3', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital San Lorenzo'],
            ['dependency'=>'Hospital de Capiatá',           'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Capiatá'],
            ['dependency'=>'Hospital de Itauguá',           'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Itauguá'],
            ['dependency'=>'Hospital de Lambaré',           'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Lambaré'],
            ['dependency'=>'Hospital de Fernando de la Mora','tipo'=>'H.',    'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Fernando de la Mora'],
            ['dependency'=>'Hospital de Limpio',            'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Limpio'],
            ['dependency'=>'Hospital de Mariano Roque Alonso','tipo'=>'H.',   'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital M.R. Alonso'],
            ['dependency'=>'Hospital de Ñemby',             'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Ñemby'],
            ['dependency'=>'Hospital de Guarambaré',        'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Guarambaré'],
            ['dependency'=>'Hospital de Villeta',           'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Villeta'],
            ['dependency'=>'Hospital de Ypané',             'tipo'=>'H.',     'nivel'=>'N2', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Director Hospital Ypané'],
            ['dependency'=>'C.P. Yrendagüe',                'tipo'=>'C.P.',   'nivel'=>'N1', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Jefe C.P. Yrendagüe'],
            ['dependency'=>'C.P. Nanawa',                   'tipo'=>'C.P.',   'nivel'=>'N1', 'tenencia'=>'PROPIO',       'aop'=>true,  'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Jefe C.P. Nanawa'],
            ['dependency'=>'C.S. San Antonio',              'tipo'=>'C.S.',   'nivel'=>'N1', 'tenencia'=>'PROPIO',       'aop'=>false, 'region'=>'ASUNCIÓN Y CENTRAL', 'manager'=>'Jefe C.S. San Antonio'],
        ];

        foreach ($establecimientos as $e) {
            $this->crearEstablecimiento($dirCentral, $e);
            $creados++;
        }

        // ── REGIÓN ORIENTAL → Dirección de Hospitales Interior ───────────────
        $interior = [
            // CONCEPCIÓN
            ['dependency'=>'Hospital Regional de Concepción','tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director H.R. Concepción'],
            ['dependency'=>'Hospital de Horqueta',           'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Horqueta'],
            ['dependency'=>'U.S. Loreto',                    'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Loreto'],
            // SAN PEDRO
            ['dependency'=>'Hospital Regional de San Pedro', 'tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',   'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director H.R. San Pedro'],
            ['dependency'=>'Hospital de Lima',               'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Lima'],
            ['dependency'=>'U.S. Capiibary',                 'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Capiibary'],
            // CORDILLERA
            ['dependency'=>'Hospital de Caacupé',            'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Caacupé'],
            ['dependency'=>'Hospital de Altos',              'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Altos'],
            ['dependency'=>'Hospital de Piribebuy',          'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Piribebuy'],
            ['dependency'=>'Hospital de Atyrá',              'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Atyrá'],
            // GUAIRÁ
            ['dependency'=>'Hospital Regional de Villarrica','tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',   'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director H.R. Villarrica'],
            ['dependency'=>'Hospital de Coronel Oviedo',     'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Coronel Oviedo'],
            // CAAGUAZÚ
            ['dependency'=>'Hospital Regional de Caaguazú',  'tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',   'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director H.R. Caaguazú'],
            ['dependency'=>'U.S. Dr. Pedro P. Peña',         'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Pedro P. Peña'],
            // CAAZAPÁ
            ['dependency'=>'Hospital de Caazapá',            'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital Caazapá'],
            ['dependency'=>'U.S. Abaí',                      'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Abaí'],
            // MISIONES
            ['dependency'=>'Hospital de San Juan Bautista',  'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital San Juan Bautista'],
            ['dependency'=>'Hospital de Ayolas',             'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Ayolas'],
            // PARAGUARÍ
            ['dependency'=>'Hospital de Paraguarí',          'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital Paraguarí'],
            ['dependency'=>'Hospital de Carapeguá',          'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Carapeguá'],
            ['dependency'=>'U.S. Yaguarón',                  'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Yaguarón'],
            // ALTO PARANÁ
            ['dependency'=>'Hospital Regional de Alto Paraná','tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',  'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director H.R. Alto Paraná'],
            ['dependency'=>'Hospital de Presidente Franco',  'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Presidente Franco'],
            ['dependency'=>'Hospital de Hernandarias',       'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Hernandarias'],
            ['dependency'=>'U.S. Minga Guazú',               'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Minga Guazú'],
            // ÑEEMBUCÚ
            ['dependency'=>'Hospital de Pilar',              'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital Pilar'],
            ['dependency'=>'U.S. Alberdi',                   'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Alberdi'],
            // AMAMBAY
            ['dependency'=>'Hospital de Pedro Juan Caballero','tipo'=>'H.','nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital Pedro Juan Caballero'],
            ['dependency'=>'U.S. Bella Vista',               'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'ORIENTAL','manager'=>'Jefe U.S. Bella Vista'],
            // CANINDEYÚ
            ['dependency'=>'Hospital de Salto del Guairá',   'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>true, 'region'=>'ORIENTAL','manager'=>'Director Hospital Salto del Guairá'],
            ['dependency'=>'Hospital de Katueté',            'tipo'=>'H.', 'nivel'=>'N1','tenencia'=>'CONVENIO',  'aop'=>false,'region'=>'ORIENTAL','manager'=>'Director Hospital Katueté'],
            // REGIÓN OCCIDENTAL
            ['dependency'=>'Hospital Benjamin Aceval',       'tipo'=>'H.R.','nivel'=>'N3','tenencia'=>'PROPIO',   'aop'=>true, 'region'=>'OCCIDENTAL','manager'=>'Director H.R. Benjamin Aceval'],
            ['dependency'=>'Hospital de Villa Hayes',        'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Director Hospital Villa Hayes'],
            ['dependency'=>'U.S. Pozo Colorado',             'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Jefe U.S. Pozo Colorado'],
            ['dependency'=>'Hospital de Loma Plata',         'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'CONVENIO',  'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Director Hospital Loma Plata'],
            ['dependency'=>'Hospital de Filadelfia',         'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'CONVENIO',  'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Director Hospital Filadelfia'],
            ['dependency'=>'U.S. Mariscal Estigarribia',     'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'TERCERIZADO','aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Jefe U.S. Mariscal Estigarribia'],
            ['dependency'=>'Hospital de Fuerte Olimpo',      'tipo'=>'H.', 'nivel'=>'N2','tenencia'=>'PROPIO',    'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Director Hospital Fuerte Olimpo'],
            ['dependency'=>'U.S. Bahía Negra',               'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'TERCERIZADO','aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Jefe U.S. Bahía Negra'],
            ['dependency'=>'U.S. Puerto Casado',             'tipo'=>'U.S.','nivel'=>'N1','tenencia'=>'PROPIO',   'aop'=>false,'region'=>'OCCIDENTAL','manager'=>'Jefe U.S. Puerto Casado'],
        ];

        foreach ($interior as $e) {
            $this->crearEstablecimiento($dirInterior, $e);
            $creados++;
        }

        $this->command->info("✅ {$creados} establecimientos creados.");
        $this->command->table(
            ['Dirección', 'Establecimientos'],
            [
                ['Área Central', Organigrama::whereDescendantOf($dirCentral)->whereNotNull('tipo_establecimiento')->count()],
                ['Interior',     Organigrama::whereDescendantOf($dirInterior)->whereNotNull('tipo_establecimiento')->count()],
            ]
        );
    }

    private function crearEstablecimiento(Organigrama $padre, array $data): Organigrama
    {
        // Generar email placeholder único basado en el nombre
        $emailSlug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '.', $data['dependency']));
        $emailSlug = preg_replace('/\.+/', '.', trim($emailSlug, '.'));

        $nodo = new Organigrama([
            'dependency'           => $data['dependency'],
            'manager'              => $data['manager'] ?? null,
            'email'                => $emailSlug . '@ips.gov.py',
            'phone'                => '000000',
            'tipo_establecimiento' => $data['tipo'],
            'nivel_complejidad'    => $data['nivel'],
            'tenencia'             => $data['tenencia'],
            'tiene_aop'            => $data['aop'],
            'region'               => $data['region'],
        ]);
        $padre->appendNode($nodo);
        $this->command->line("  ✓ [{$data['tipo']}] {$data['dependency']} ({$data['nivel']})");
        return $nodo;
    }
}
