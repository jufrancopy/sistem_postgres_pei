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
    protected ?string $authenticatedHtml = null;
    protected \GuzzleHttp\Cookie\CookieJar $cookieJar;

    public function __construct(?string $baseUrl = null, ?string $username = null, ?string $password = null)
    {
        $defaultUrl = env('MECIP_BPM_BASE_URL', 'https://servicios.ips.gov.py/ips/servlet/WSNavigatorPlus');
        $this->baseUrl   = rtrim($baseUrl ?? config('services.mecip_bpm.base_url', $defaultUrl), '/');
        $this->username  = $username ?? config('services.mecip_bpm.user', env('MECIP_BPM_USER', ''));
        $this->password  = $password ?? config('services.mecip_bpm.password', env('MECIP_BPM_PASSWORD', ''));
        $this->cookieJar = new \GuzzleHttp\Cookie\CookieJar();
    }

    public function getCookieJar(): \GuzzleHttp\Cookie\CookieJar
    {
        return $this->cookieJar;
    }

    public function getAuthenticatedHtml(): string
    {
        return $this->authenticatedHtml ?? '';
    }

    /**
     * Escribe logs de forma segura sin fallar por permisos de archivo
     */
    protected function safeLog(string $level, string $message): void
    {
        try {
            Log::$level($message);
        } catch (\Throwable $e) {
            // Silencioso en caso de permisos de archivo en storage/logs/
        }
    }

    /**
     * Inicia sesión en el sistema BPM Servlet legado y obtiene la cookie JSESSIONID
     */
    public function authenticate(): bool
    {
        if (empty($this->username) || empty($this->password)) {
            $this->safeLog('warning', '[MECIP Scraper] No se configuraron credenciales institucionales para BPM.');
            return false;
        }

        try {
            $loginUrl = str_contains($this->baseUrl, 'WSNavigatorPlus') ? $this->baseUrl : "{$this->baseUrl}/WSNavigatorPlus";
            if (!str_contains($loginUrl, '_APPNAME=bpm')) {
                $loginUrl .= (str_contains($loginUrl, '?') ? '&' : '?') . '_APPNAME=bpm';
            }
            if (!str_contains($loginUrl, '_ALLOWRESUBMIT=TRUE')) {
                $loginUrl .= '&_ALLOWRESUBMIT=TRUE';
            }

            // Petición GET previa para obtener la página y cualquier token o cookie inicial
            $initialReq = Http::withOptions(['cookies' => $this->cookieJar, 'verify' => false, 'timeout' => 15])->get($loginUrl);
            $initialHtml = $initialReq->body();

            // Extraer todos los campos ocultos del formulario inicial
            $dom = new \DOMDocument();
            @$dom->loadHTML(mb_convert_encoding($initialHtml, 'HTML-ENTITIES', 'UTF-8'));
            $xpath = new \DOMXPath($dom);

            $postData = [];
            $hiddenInputs = $xpath->query("//form//input[@type='hidden']");
            if ($hiddenInputs && $hiddenInputs->length > 0) {
                foreach ($hiddenInputs as $input) {
                    $name  = $input->getAttribute('name');
                    $value = $input->getAttribute('value');
                    if (!empty($name)) {
                        $postData[$name] = $value;
                    }
                }
            }

            // Configurar parámetros del formulario Cytera WSNavigatorPlus con ShredPassword SHA1/HMAC
            $loginCryptKey = $postData['login_crypt_key'] ?? '';
            $sTemp = strtolower(sha1($this->password));
            $loginCryptPassword = hash_hmac('sha1', $loginCryptKey, $sTemp);

            $postData['_APPNAME']              = 'bpm';
            $postData['_PAGE']                 = 'bpm';
            $postData['_FORM']                 = 'login.Login';
            $postData['_PROCESS']              = 'TRUE';
            $postData['_BRANCH']               = 'ROOT';
            $postData['m_btn_user']            = $this->username;
            $postData['m_btn_password']        = ''; // Vacío como en la función JS ShredPassword
            $postData['m_btn_login']           = 'Iniciar Sesión';
            $postData['login_encode_password'] = $this->password;
            $postData['login_crypt_password']  = $loginCryptPassword;
            $postData['user']                  = $this->username;

            // Petición POST autenticada al Servlet Cytera NavigatorPlus del IPS
            $response = Http::asForm()
                ->withOptions([
                    'cookies' => $this->cookieJar,
                    'verify'  => false,
                    'allow_redirects' => true,
                    'timeout' => 20,
                ])
                ->post($loginUrl, $postData);

            if ($response->successful()) {
                $cookies = $response->cookies();
                $jsessionCookie = $cookies->getCookieByName('JSESSIONID');

                if ($jsessionCookie) {
                    $this->jsessionId = $jsessionCookie->getValue();
                }

                $body = $response->body();
                $this->authenticatedHtml = $body;

                if (str_contains($body, 'Contraseña Incorrectos') || str_contains($body, 'Usuario o Contraseña Inválidos')) {
                    $this->safeLog('error', "[MECIP Scraper] Error de Credenciales en BPM IPS: Usuario o Contraseña Incorrectos para '{$this->username}'.");
                    return false;
                }

                if ($this->jsessionId || str_contains($body, 'Bienvenido') || str_contains($body, 'Gobernanza') || str_contains($body, 'Franco Baez') || str_contains($body, 'm_process_id')) {
                    $this->safeLog('info', "[MECIP Scraper] Autenticación BPM confirmada en Cytera IPS para '{$this->username}'.");
                    return true;
                }
            }

            $this->safeLog('error', "[MECIP Scraper] Error al autenticar en BPM Servlets: Status " . $response->status());
            return false;
        } catch (\Throwable $e) {
            $this->safeLog('error', "[MECIP Scraper] Excepción durante autenticación BPM: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Extrae y navega hacia un proceso activo mediante m_process_id y parsea el HTML resultante
     */
    public function scrapeProcess(string $processId, ?string $numeroCaso = null): ?MecipCaso
    {
        if (!$this->jsessionId && !$this->authenticate()) {
            $this->safeLog('info', "[MECIP Scraper] Modo fallback sin sesión remota previa (Parseando HTML directo si se provee).");
        }

        try {
            $baseUrl = str_contains($this->baseUrl, 'WSNavigatorPlus') ? $this->baseUrl : "{$this->baseUrl}/WSNavigatorPlus";
            
            // Intentar las 3 URLs estándar de Cytera BPM para visualizar procesos
            $endpoints = [
                "{$baseUrl}?_APPNAME=bpm&m_process_id={$processId}",
                "{$baseUrl}?_APPNAME=bpm&_PAGE=claim.UpdateProcess&m_process_id={$processId}",
                "{$baseUrl}?_APPNAME=bpm&_PAGE=cyrw_list_client_product.SearchProcess_pag&m_process_id={$processId}",
            ];

            foreach ($endpoints as $url) {
                $response = Http::asForm()
                    ->withOptions([
                        'cookies' => $this->cookieJar,
                        'verify'  => false,
                        'timeout' => 30,
                    ])
                    ->post($url, [
                        '_APPNAME'     => 'bpm',
                        'm_process_id' => $processId,
                        'action'       => 'view_process',
                    ]);

                if ($response->successful()) {
                    $html = $response->body();
                    $caso = $this->parseAndSyncHtml($html, $numeroCaso ?? "CASO-BPM-{$processId}", $processId);
                    if ($caso && ($caso->componentes->count() > 0 || !empty($caso->subproceso))) {
                        return $caso;
                    }
                }
            }

            $this->safeLog('warning', "[MECIP Scraper] Petición a m_process_id={$processId} no halló tablas en endpoints estándar.");
            return null;
        } catch (\Throwable $e) {
            $this->safeLog('error', "[MECIP Scraper] Excepción al extraer proceso {$processId}: " . $e->getMessage());
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
