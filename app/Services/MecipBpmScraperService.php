<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Mecip\MecipCaso;
use App\Models\Mecip\MecipCasoComponente;
use App\Models\Mecip\MecipCasoActividad;
use App\Models\Mecip\MecipCasoTarea;
use App\Models\Mecip\MecipCasoCambio;
use App\Events\MecipNotificacionEvent;

class MecipBpmScraperService
{
    protected string $baseUrl;
    protected string $username;
    protected string $password;
    protected ?string $jsessionId = null;

    public function __construct(?string $baseUrl = null, ?string $username = null, ?string $password = null)
    {
        $this->baseUrl  = rtrim($baseUrl ?? config('services.mecip_bpm.base_url', env('MECIP_BPM_BASE_URL', 'http://bpm.ips.gov.py')), '/');
        $this->username = $username ?? config('services.mecip_bpm.user', env('MECIP_BPM_USER', ''));
        $this->password = $password ?? config('services.mecip_bpm.password', env('MECIP_BPM_PASSWORD', ''));
    }

    /**
     * Inicia sesión en el sistema BPM Servlet legado y obtiene la cookie JSESSIONID
     */
    public function authenticate(): bool
    {
        if (empty($this->username) || empty($this->password)) {
            Log::warning('[MECIP Scraper] No se configuraron credenciales institucionales para BPM.');
            return false;
        }

        try {
            $loginUrl = "{$this->baseUrl}/WSNavigatorPlus";

            // Petición POST autenticada al Servlet WSNavigatorPlus
            $response = Http::asForm()
                ->withOptions([
                    'cookies' => true,
                    'verify'  => false,
                    'allow_redirects' => true,
                    'timeout' => 20,
                ])
                ->post($loginUrl, [
                    '_APPNAME' => 'bpm',
                    'user'     => $this->username,
                    'password' => $this->password,
                    'action'   => 'login',
                ]);

            if ($response->successful()) {
                // Capturar JSESSIONID de los headers Set-Cookie o del cliente HTTP
                $cookies = $response->cookies();
                $jsessionCookie = $cookies->getCookieByName('JSESSIONID');

                if ($jsessionCookie) {
                    $this->jsessionId = $jsessionCookie->getValue();
                    Log::info("[MECIP Scraper] Sesión BPM iniciada exitosamente. JSESSIONID: {$this->jsessionId}");
                    return true;
                }

                // Fallback: Verificar si el body indica éxito de autenticación
                if (str_contains($response->body(), 'JSESSIONID') || str_contains($response->body(), 'm_process_id') || str_contains($response->body(), 'Bienvenido')) {
                    Log::info("[MECIP Scraper] Autenticación BPM confirmada en cuerpo HTML.");
                    return true;
                }
            }

            Log::error("[MECIP Scraper] Error al autenticar en BPM Servlets: Status " . $response->status());
            return false;
        } catch (\Throwable $e) {
            Log::error("[MECIP Scraper] Excepción durante autenticación BPM: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrae y navega hacia un proceso activo mediante m_process_id y parsea el HTML resultante
     */
    public function scrapeProcess(string $processId, ?string $numeroCaso = null): ?MecipCaso
    {
        if (!$this->jsessionId && !$this->authenticate()) {
            Log::info("[MECIP Scraper] Modo fallback sin sesión remota previa (Parseando HTML directo si se provee).");
        }

        try {
            $targetUrl = "{$this->baseUrl}/WSNavigatorPlus";

            $response = Http::asForm()
                ->withOptions([
                    'cookies' => true,
                    'verify'  => false,
                    'timeout' => 30,
                ])
                ->post($targetUrl, [
                    '_APPNAME'     => 'bpm',
                    'm_process_id' => $processId,
                    'action'       => 'view_process',
                ]);

            if ($response->successful()) {
                $html = $response->body();
                return $this->parseAndSyncHtml($html, $numeroCaso ?? "CASO-BPM-{$processId}", $processId);
            }

            Log::error("[MECIP Scraper] Petición a m_process_id={$processId} retornó estatus " . $response->status());
            return null;
        } catch (\Throwable $e) {
            Log::error("[MECIP Scraper] Excepción al extraer proceso {$processId}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Parsea un fragmento o documento completo HTML del formulario MECIP BPM y sincroniza en la BD local
     */
    public function parseAndSyncHtml(string $html, string $numeroCaso, ?string $processId = null): MecipCaso
    {
        $dom = new \DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8'));
        $xpath = new \DOMXPath($dom);

        // 1. Extraer Metadatos del Caso/Subproceso
        $macroproceso       = $this->extractInputOrText($xpath, "//input[@name='macroproceso'] | //span[@id='lbl_macroproceso']", 'PROCESO DE GOBERNANZA');
        $proceso            = $this->extractInputOrText($xpath, "//input[@name='proceso'] | //span[@id='lbl_proceso']", 'GESTIÓN ESTRATÉGICA E INSTITUCIONAL');
        $subproceso         = $this->extractInputOrText($xpath, "//input[@name='subproceso'] | //span[@id='lbl_subproceso']", 'Diseño y Actualización de Estructuras');
        $codigoSubproceso   = $this->extractInputOrText($xpath, "//input[@name='codigo_subproceso'] | //span[@id='lbl_codigo']", 'GEI-01');
        $version            = $this->extractInputOrText($xpath, "//input[@name='version'] | //span[@id='lbl_version']", '1.0');
        $responsableAnalisis = $this->extractInputOrText($xpath, "//input[@name='responsable'] | //span[@id='lbl_responsable']", 'Analista MECIP IPS');

        // 2. Extraer Productos e Insumos de Tablas HTML
        $productos = $this->extractTableRows($xpath, "//table[contains(@id, 'tabla_productos') or contains(@class, 'productos') or contains(., 'Productos')]");
        $insumos   = $this->extractTableRows($xpath, "//table[contains(@id, 'tabla_insumos') or contains(@class, 'insumos') or contains(., 'Insumos')]");

        // 3. Extraer Actividades y Tareas
        $actividades = $this->extractTableRows($xpath, "//table[contains(@id, 'tabla_actividades') or contains(@class, 'actividades') or contains(., 'Actividades')]");

        // 4. Iniciar Sincronización en Base de Datos Local
        DB::beginTransaction();
        try {
            $caso = MecipCaso::updateOrCreate(
                ['numero_caso' => $numeroCaso],
                [
                    'codigo_subproceso'    => $codigoSubproceso,
                    'macroproceso'         => $macroproceso,
                    'proceso'              => $proceso,
                    'subproceso'           => $subproceso,
                    'version'              => $version,
                    'fecha_elaboracion'    => now(),
                    'responsable_analisis' => $responsableAnalisis,
                    'automatico_flag'      => true,
                    'estado_flujo'         => 'en_analisis',
                ]
            );

            // Sincronizar Componentes (Productos e Insumos)
            $caso->componentes()->delete();

            $orden = 1;
            foreach ($productos as $p) {
                MecipCasoComponente::create([
                    'mecip_caso_id'          => $caso->id,
                    'tipo'                   => 'producto',
                    'nombre'                 => $p['nombre'] ?? ($p['col_0'] ?? 'Producto Sincronizado'),
                    'entidad_origen_destino' => $p['entidad'] ?? ($p['col_1'] ?? 'Área Destino'),
                    'descripcion'            => $p['descripcion'] ?? ($p['col_2'] ?? ''),
                    'orden'                  => $orden++,
                ]);
            }

            foreach ($insumos as $i) {
                MecipCasoComponente::create([
                    'mecip_caso_id'          => $caso->id,
                    'tipo'                   => 'insumo',
                    'nombre'                 => $i['nombre'] ?? ($i['col_0'] ?? 'Insumo Sincronizado'),
                    'entidad_origen_destino' => $i['entidad'] ?? ($i['col_1'] ?? 'Área Origen'),
                    'descripcion'            => $i['descripcion'] ?? ($i['col_2'] ?? ''),
                    'orden'                  => $orden++,
                ]);
            }

            // Sincronizar Actividades
            if (!empty($actividades)) {
                $caso->actividades()->delete();
                $actOrden = 1;
                foreach ($actividades as $act) {
                    MecipCasoActividad::create([
                        'mecip_caso_id'      => $caso->id,
                        'codigo_actividad'   => $act['codigo'] ?? "ACT-{$actOrden}",
                        'nombre_actividad'   => $act['nombre'] ?? ($act['col_0'] ?? 'Actividad Sincronizada'),
                        'responsable'        => $act['responsable'] ?? ($act['col_1'] ?? 'Responsable Asignado'),
                        'estado'             => 'PENDIENTE',
                        'orden'              => $actOrden++,
                    ]);
                }
            }

            // Registrar Auditoría de Cambio
            MecipCasoCambio::create([
                'mecip_caso_id'    => $caso->id,
                'user_id'          => auth()->id() ?? 1,
                'tipo_cambio'      => 'SINCRONIZACION_SCRAPING_BPM',
                'resumen_cambio'   => "Sincronización automática mediante cliente HTTP/Scraping BPM (m_process_id: {$processId}).",
                'detalles_json'    => json_encode(['process_id' => $processId, 'productos_count' => count($productos), 'insumos_count' => count($insumos)]),
            ]);

            DB::commit();

            // Transmitir Evento en Vivo
            event(new MecipNotificacionEvent($caso, 'SINCRONIZACION_BPM', "Caso {$caso->numero_caso} sincronizado exitosamente desde el BPM IPS."));

            Log::info("[MECIP Scraper] Caso {$caso->numero_caso} sincronizado exitosamente en BD local (ID: {$caso->id}).");
            return $caso;
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error("[MECIP Scraper] Error al guardar datos en BD local: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extrae el valor de un input o elemento de texto vía XPath
     */
    protected function extractInputOrText(\DOMXPath $xpath, string $expression, string $default = ''): string
    {
        $nodes = $xpath->query($expression);
        if ($nodes && $nodes->length > 0) {
            $node = $nodes->item(0);
            if ($node->hasAttribute('value')) {
                return trim($node->getAttribute('value'));
            }
            return trim($node->textContent);
        }
        return $default;
    }

    /**
     * Extrae filas estructuradas de una tabla HTML
     */
    protected function extractTableRows(\DOMXPath $xpath, string $tableQuery): array
    {
        $rowsData = [];
        $tables = $xpath->query($tableQuery);

        if (!$tables || $tables->length === 0) {
            return $rowsData;
        }

        $table = $tables->item(0);
        $rows = $xpath->query(".//tr[position() > 1]", $table);

        foreach ($rows as $row) {
            $cols = $xpath->query(".//td", $row);
            if ($cols && $cols->length > 0) {
                $rowData = [];
                for ($i = 0; $i < $cols->length; $i++) {
                    $val = trim($cols->item($i)->textContent);
                    $rowData["col_{$i}"] = $val;
                    if ($i === 0) $rowData['nombre'] = $val;
                    if ($i === 1) $rowData['entidad'] = $val;
                    if ($i === 2) $rowData['descripcion'] = $val;
                }
                $rowsData[] = $rowData;
            }
        }

        return $rowsData;
    }
}
