<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InstitucionesParaguaySeeder extends Seeder
{
    public function run(): void
    {
        $instituciones = [
            // ── Poderes del Estado ────────────────────────────────────────
            ['nombre' => 'Poder Ejecutivo',                                                         'sigla' => null,      'tipo' => 'poder'],
            ['nombre' => 'Poder Legislativo - Congreso Nacional',                                   'sigla' => null,      'tipo' => 'poder'],
            ['nombre' => 'Poder Judicial',                                                          'sigla' => null,      'tipo' => 'poder'],
            ['nombre' => 'Cámara de Senadores',                                                     'sigla' => null,      'tipo' => 'poder'],
            ['nombre' => 'Cámara de Diputados',                                                     'sigla' => null,      'tipo' => 'poder'],

            // ── Ministerios ───────────────────────────────────────────────
            ['nombre' => 'Ministerio de Salud Pública y Bienestar Social',                          'sigla' => 'MSPBS',   'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Hacienda',                                                  'sigla' => 'MH',      'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Educación y Ciencias',                                      'sigla' => 'MEC',     'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio del Interior',                                                 'sigla' => null,      'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Relaciones Exteriores',                                     'sigla' => 'MRE',     'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Justicia',                                                  'sigla' => null,      'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Defensa Nacional',                                          'sigla' => null,      'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Obras Públicas y Comunicaciones',                           'sigla' => 'MOPC',    'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Agricultura y Ganadería',                                   'sigla' => 'MAG',     'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Industria y Comercio',                                      'sigla' => 'MIC',     'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio del Trabajo, Empleo y Seguridad Social',                       'sigla' => 'MTESS',   'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Tecnologías de la Información y Comunicación',              'sigla' => 'MITIC',   'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Urbanismo, Vivienda y Hábitat',                             'sigla' => 'MUVH',    'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de la Niñez y la Adolescencia',                                'sigla' => 'MINNA',   'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de la Mujer',                                                  'sigla' => null,      'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio del Ambiente y Desarrollo Sostenible',                         'sigla' => 'MADES',   'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Desarrollo Social',                                         'sigla' => 'MDS',     'tipo' => 'ministerio'],
            ['nombre' => 'Ministerio de Economía',                                                  'sigla' => null,      'tipo' => 'ministerio'],

            // ── Secretarías de Estado ─────────────────────────────────────
            ['nombre' => 'Secretaría Nacional Anticorrupción',                                      'sigla' => 'SENAC',   'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría de Emergencia Nacional',                                       'sigla' => 'SEN',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de Cultura',                                          'sigla' => 'SNC',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de Deportes',                                         'sigla' => 'SND',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de Turismo',                                          'sigla' => 'SENATUR', 'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría de Planificación para el Desarrollo Económico y Social',       'sigla' => 'STP',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de la Vivienda y el Hábitat',                         'sigla' => 'SENAVITAT','tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional por los Derechos Humanos de las Personas con Discapacidad', 'sigla' => 'SENADIS', 'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría de Acción Social',                                             'sigla' => 'SAS',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de la Juventud',                                      'sigla' => 'SNJ',     'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de Ciencia y Tecnología',                             'sigla' => 'SENACYT', 'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional de Inteligencia',                                     'sigla' => 'SENAD',   'tipo' => 'secretaria'],
            ['nombre' => 'Secretaría Nacional Antidrogas',                                          'sigla' => 'SENAD',   'tipo' => 'secretaria'],

            // ── Entes Descentralizados / Autárquicos ──────────────────────
            ['nombre' => 'Instituto de Previsión Social',                                           'sigla' => 'IPS',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Caja de Jubilaciones y Pensiones del Personal Municipal',                 'sigla' => 'CAJAMUN', 'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Caja de Jubilaciones y Pensiones de Empleados Bancarios',                 'sigla' => 'CAJABAN', 'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Caja Fiscal - Ministerio de Hacienda',                                    'sigla' => null,      'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Banco Central del Paraguay',                                              'sigla' => 'BCP',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Banco Nacional de Fomento',                                               'sigla' => 'BNF',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Banco de Desarrollo del Paraguay',                                        'sigla' => 'BDP',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Crédito Agrícola de Habilitación',                                        'sigla' => 'CAH',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Agencia Financiera de Desarrollo',                                        'sigla' => 'AFD',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional de Estadística',                                       'sigla' => 'INE',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional de Tecnología, Normalización y Metrología',            'sigla' => 'INTN',    'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional de Cooperativismo',                                    'sigla' => 'INCOOP',  'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional de Desarrollo Rural y de la Tierra',                   'sigla' => 'INDERT',  'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional de Alimentación y Nutrición',                          'sigla' => 'INAN',    'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Nacional del Indígena',                                         'sigla' => 'INDI',    'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Instituto Paraguayo del Indígena',                                        'sigla' => 'INDI',    'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Servicio Nacional de Calidad y Salud Animal',                             'sigla' => 'SENACSA', 'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Servicio Nacional de Calidad Vegetal y de Semillas',                      'sigla' => 'SENAVE',  'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Dirección Nacional de Aeronáutica Civil',                                 'sigla' => 'DINAC',   'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Dirección Nacional de Aduanas',                                           'sigla' => 'DNA',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Subsecretaría de Estado de Tributación',                                  'sigla' => 'SET',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Contraloría General de la República',                                     'sigla' => 'CGR',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Defensoría del Pueblo',                                                   'sigla' => null,      'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Ministerio Público',                                                      'sigla' => null,      'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Procuraduría General de la República',                                    'sigla' => null,      'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Tribunal Superior de Justicia Electoral',                                 'sigla' => 'TSJE',    'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Jurado de Enjuiciamiento de Magistrados',                                 'sigla' => 'JEM',     'tipo' => 'ente_descentralizado'],
            ['nombre' => 'Consejo de la Magistratura',                                              'sigla' => null,      'tipo' => 'ente_descentralizado'],

            // ── Empresas Públicas ─────────────────────────────────────────
            ['nombre' => 'Administración Nacional de Electricidad',                                 'sigla' => 'ANDE',    'tipo' => 'empresa_publica'],
            ['nombre' => 'Empresa de Servicios Sanitarios del Paraguay',                            'sigla' => 'ESSAP',   'tipo' => 'empresa_publica'],
            ['nombre' => 'Petróleos Paraguayos',                                                    'sigla' => 'PETROPAR','tipo' => 'empresa_publica'],
            ['nombre' => 'Industria Nacional del Cemento',                                          'sigla' => 'INC',     'tipo' => 'empresa_publica'],
            ['nombre' => 'Ferrocarriles del Paraguay S.A.',                                         'sigla' => 'FEPASA', 'tipo' => 'empresa_publica'],
            ['nombre' => 'Correo Paraguayo',                                                        'sigla' => null,      'tipo' => 'empresa_publica'],
            ['nombre' => 'Dirección Nacional de Contrataciones Públicas',                           'sigla' => 'DNCP',    'tipo' => 'empresa_publica'],

            // ── Organismos Internacionales con presencia en Paraguay ───────
            ['nombre' => 'Organización Panamericana de la Salud',                                   'sigla' => 'OPS/OMS', 'tipo' => 'organismo_internacional'],
            ['nombre' => 'Programa de las Naciones Unidas para el Desarrollo',                      'sigla' => 'PNUD',    'tipo' => 'organismo_internacional'],
            ['nombre' => 'Fondo de las Naciones Unidas para la Infancia',                           'sigla' => 'UNICEF',  'tipo' => 'organismo_internacional'],
            ['nombre' => 'Organización Internacional del Trabajo',                                  'sigla' => 'OIT',     'tipo' => 'organismo_internacional'],
            ['nombre' => 'Banco Interamericano de Desarrollo',                                      'sigla' => 'BID',     'tipo' => 'organismo_internacional'],
            ['nombre' => 'Banco Mundial',                                                           'sigla' => 'BM',      'tipo' => 'organismo_internacional'],
            ['nombre' => 'Fondo Monetario Internacional',                                           'sigla' => 'FMI',     'tipo' => 'organismo_internacional'],
            ['nombre' => 'Agencia de los Estados Unidos para el Desarrollo Internacional',          'sigla' => 'USAID',   'tipo' => 'organismo_internacional'],
            ['nombre' => 'Cooperación Alemana al Desarrollo',                                       'sigla' => 'GIZ',     'tipo' => 'organismo_internacional'],
            ['nombre' => 'Agencia Española de Cooperación Internacional para el Desarrollo',        'sigla' => 'AECID',   'tipo' => 'organismo_internacional'],
            ['nombre' => 'Fondo de Población de las Naciones Unidas',                               'sigla' => 'UNFPA',   'tipo' => 'organismo_internacional'],

            // ── Sector Privado / Gremios ──────────────────────────────────
            ['nombre' => 'Federación de la Producción, la Industria y el Comercio',                 'sigla' => 'FEPRINCO','tipo' => 'gremio'],
            ['nombre' => 'Unión Industrial Paraguaya',                                              'sigla' => 'UIP',     'tipo' => 'gremio'],
            ['nombre' => 'Cámara de Comercio y Servicios de Paraguay',                              'sigla' => null,      'tipo' => 'gremio'],
            ['nombre' => 'Asociación Rural del Paraguay',                                           'sigla' => 'ARP',     'tipo' => 'gremio'],
            ['nombre' => 'Coordinadora Agrícola del Paraguay',                                      'sigla' => 'CAP',     'tipo' => 'gremio'],
            ['nombre' => 'Central Nacional de Trabajadores',                                        'sigla' => 'CNT',     'tipo' => 'gremio'],
            ['nombre' => 'Central Unitaria de Trabajadores Auténtica',                              'sigla' => 'CUT-A',   'tipo' => 'gremio'],
            ['nombre' => 'Confederación Paraguaya de Trabajadores',                                 'sigla' => 'CPT',     'tipo' => 'gremio'],

            // ── Universidades ─────────────────────────────────────────────
            ['nombre' => 'Universidad Nacional de Asunción',                                        'sigla' => 'UNA',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Católica Nuestra Señora de la Asunción',                      'sigla' => 'UCA',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Autónoma de Asunción',                                        'sigla' => 'UAA',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Americana',                                                   'sigla' => null,      'tipo' => 'universidad'],
            ['nombre' => 'Universidad del Norte',                                                   'sigla' => 'UNINORTE','tipo' => 'universidad'],
            ['nombre' => 'Universidad Columbia del Paraguay',                                       'sigla' => null,      'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional del Este',                                           'sigla' => 'UNE',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional de Itapúa',                                          'sigla' => 'UNI',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional de Pilar',                                           'sigla' => 'UNP',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional de Concepción',                                      'sigla' => 'UNC',     'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional de Caaguazú',                                        'sigla' => 'UNCA',    'tipo' => 'universidad'],
            ['nombre' => 'Universidad Nacional de Villarrica del Espíritu Santo',                   'sigla' => 'UNVES',   'tipo' => 'universidad'],

            // ── Gobiernos Locales ─────────────────────────────────────────
            ['nombre' => 'Municipalidad de Asunción',                                               'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación del Departamento Central',                                    'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Alto Paraná',                                              'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Itapúa',                                                   'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Caaguazú',                                                 'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Concepción',                                               'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de San Pedro',                                                'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Cordillera',                                               'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Guairá',                                                   'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Caazapá',                                                  'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Misiones',                                                 'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Paraguarí',                                                'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Alto Paraguay',                                            'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Boquerón',                                                 'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Presidente Hayes',                                         'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Amambay',                                                  'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Canindeyú',                                                'sigla' => null,      'tipo' => 'gobierno_local'],
            ['nombre' => 'Gobernación de Ñeembucú',                                                 'sigla' => null,      'tipo' => 'gobierno_local'],
        ];

        $now = now();
        $rows = array_map(fn($i) => array_merge($i, ['activo' => true, 'created_at' => $now, 'updated_at' => $now]), $instituciones);

        // Insertar en chunks para evitar límites de SQL
        foreach (array_chunk($rows, 50) as $chunk) {
            DB::table('instituciones_paraguay')->insert($chunk);
        }
    }
}
