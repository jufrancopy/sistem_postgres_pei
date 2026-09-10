<?php

namespace App\Console\Commands;

use App\Models\Bioestadistica\Establecimiento as BioEstablecimiento;
use App\Models\Bioestadistica\Distrito;
use App\Models\Bioestadistica\TipoEstablecimiento;
use App\Models\Bioestadistica\GradoComplejidad;
use App\Models\Bioestadistica\AreaGestion;
use App\Models\Riiss\Establecimiento as RiissEstablecimiento;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RiissSyncBioestadisticaEstablecimientos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'riiss:sync-bioestadistica-establecimientos {--dry-run : Muestra los cambios sin aplicarlos}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza de forma no destructiva los establecimientos de RIISS con la matriz geográfica de Bioestadística';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = (bool)$this->option('dry-run');

        $this->info("=== SINCRONIZACIÓN SEGURA DE ESTABLECIMIENTOS (Bioestadística ↔ RIISS) ===");
        if ($isDryRun) {
            $this->warn("MODO SIMULACIÓN (--dry-run ACTIVO). Ningún dato será modificado.");
        }

        // 1. Cargar matriz canónica de Bioestadística
        $bioEstablecimientos = BioEstablecimiento::with([
            'distrito.departamento',
            'tipoEstablecimiento',
            'gradoComplejidad',
            'areaGestion',
            'microred'
        ])->get();

        $this->line("Cargados {$bioEstablecimientos->count()} establecimientos desde bioestadistica.establecimientos.");

        $actualizados = 0;
        $creados = 0;
        $sinCambios = 0;

        DB::beginTransaction();

        try {
            foreach ($bioEstablecimientos as $bio) {
                $codigo = trim($bio->codigo);
                if (empty($codigo)) {
                    continue;
                }

                $departamento = $bio->distrito?->departamento?->nombre;
                if (empty($departamento)) {
                    if (str_starts_with($codigo, '11-')) {
                        $departamento = 'CENTRAL';
                    } elseif (str_starts_with($codigo, '18-')) {
                        $departamento = 'ASUNCIÓN';
                    } elseif ($riissEst && !empty($riissEst->departamento)) {
                        $departamento = $riissEst->departamento;
                    }
                }
                
                // Mapeo territorial estandarizado de Dirección de Hospitales
                $areaGestionNombre = (in_array($departamento, ['CENTRAL', 'ASUNCIÓN', 'CAPITAL']) || str_starts_with($codigo, '11-') || str_starts_with($codigo, '18-')) 
                    ? 'AREA CENTRAL' 
                    : 'AREA INTERIOR';

                $riissEst = RiissEstablecimiento::find($codigo);

                // Mapear nivel de atención a entero
                $nivelNum = 1;
                if (preg_match('/(\d+)/', (string)$bio->nivel_atencion, $m)) {
                    $nivelNum = (int)$m[1];
                }

                $gradoNum = $bio->gradoComplejidad ? (int)$bio->gradoComplejidad->codigo : 1;

                $nroDepto = (int)substr($codigo, 0, 2);
                if ($nroDepto === 0 && $departamento === 'ASUNCIÓN') {
                    $nroDepto = 18;
                }

                $datos = [
                    'nombre_oficial'          => mb_substr((string)$bio->nombre, 0, 250),
                    'departamento'            => $departamento ? mb_substr((string)$departamento, 0, 80) : null,
                    'nro_departamento'        => $nroDepto ?: 18,
                    'microred'                => mb_substr((string)($bio->microred?->nombre ?? ($riissEst?->microred ?? 'Red Asistencial')), 0, 120),
                    'prestador'               => mb_substr((string)($bio->prestador ?? ($riissEst?->prestador ?? 'IPS')), 0, 60),
                    'tipologia_clasificacion' => mb_substr((string)($bio->tipoEstablecimiento?->nombre ?? ($riissEst?->tipologia_clasificacion ?? 'PUESTO SANITARIO')), 0, 150),
                    'latitude'                => $bio->latitud ?? $riissEst?->latitude,
                    'longitude'               => $bio->longitud ?? $riissEst?->longitude,
                    'area_gestion'            => $areaGestionNombre,
                    'situacion_inmueble'      => $bio->situacion_inmueble ? mb_substr((string)$bio->situacion_inmueble, 0, 60) : $riissEst?->situacion_inmueble,
                    'sistema_hospitalario'    => $bio->sistema ? mb_substr((string)$bio->sistema, 0, 60) : $riissEst?->sistema_hospitalario,
                    'codigo'                  => $bio->codigo_sih ? (int)$bio->codigo_sih : $riissEst?->codigo,
                    'nivel_atencion'          => $nivelNum,
                    'grado_complejidad'       => $gradoNum,
                    'activo'                  => true,
                ];

                if ($riissEst) {
                    // Verificar si difiere
                    $cambios = false;
                    foreach ($datos as $k => $v) {
                        if ($riissEst->$k != $v) {
                            $cambios = true;
                            break;
                        }
                    }

                    if ($cambios) {
                        if (!$isDryRun) {
                            $riissEst->update($datos);
                        }
                        $actualizados++;
                        $this->line(" [ACTUALIZADO] [{$codigo}] {$bio->nombre} ({$departamento} | {$areaGestionNombre})");
                    } else {
                        $sinCambios++;
                    }
                } else {
                    if (!$isDryRun) {
                        $datos['id_establecimiento'] = $codigo;
                        $datos['tipo_est'] = 'PS';
                        $datos['complejidad'] = 'No Hospitalario de Baja Complejidad';
                        RiissEstablecimiento::create($datos);
                    }
                    $creados++;
                    $this->info(" [CREADO EN RIISS] [{$codigo}] {$bio->nombre} ({$departamento} | {$areaGestionNombre})");
                }
            }

            // 2. Asegurar que Parque Salud 01-MP-01 / 18-MP-01 y San Miguel 18-PS-70 queden sincronizados
            $sanMiguel = RiissEstablecimiento::find('18-PS-70');
            if ($sanMiguel && !BioEstablecimiento::where('codigo', '18-PS-70')->exists()) {
                if (!$isDryRun) {
                    $distritoAsu = Distrito::where('nombre', 'ilike', '%ASUNCION%')->first();
                    $tipoPs = TipoEstablecimiento::where('nombre', 'ilike', '%PUESTO SANITARIO%')->first();
                    $areaCentral = AreaGestion::where('nombre', 'ilike', '%CENTRAL%')->first();

                    BioEstablecimiento::create([
                        'codigo'                  => '18-PS-70',
                        'nombre'                  => 'SAN MIGUEL - ANDE PS',
                        'distrito_id'             => $distritoAsu?->id ?? 249,
                        'microred_id'             => 1,
                        'tipo_establecimiento_id' => $tipoPs?->id ?? 1,
                        'area_gestion_id'         => $areaCentral?->id ?? 2,
                        'nivel_atencion'          => 'NIVEL 1',
                        'prestador'               => 'IPS',
                        'situacion_inmueble'      => 'IPS',
                        'latitud'                 => $sanMiguel->latitude,
                        'longitud'                => $sanMiguel->longitude,
                    ]);
                }
                $this->info(" [AGREGADO EN BIOESTADÍSTICA] [18-PS-70] SAN MIGUEL - ANDE PS");
            }

            if ($isDryRun) {
                DB::rollBack();
                $this->warn("\nSimulación finalizada sin persistir cambios en la base de datos.");
            } else {
                DB::commit();
                $this->info("\nTransacción confirmada y sincronización completada exitosamente.");
            }

            $this->table(
                ['Métrica', 'Cantidad'],
                [
                    ['Total Matriz Bioestadística', $bioEstablecimientos->count()],
                    ['Establecimientos Actualizados en RIISS', $actualizados],
                    ['Establecimientos Creados en RIISS', $creados],
                    ['Establecimientos Idénticos / Sin Cambios', $sinCambios],
                    ['Total Establecimientos RIISS Final', RiissEstablecimiento::count()],
                ]
            );

        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Error durante la sincronización: " . $e->getMessage());
            return 1;
        }

        return 0;
    }
}
