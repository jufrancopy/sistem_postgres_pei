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
            
            // Probar en orden de prioridad: 1. Formulario de Actividad MECIP, 2. UpdateProcess_self, 3. UpdateProcess
            $endpoints = [
                "{$baseUrl}?_APPNAME=bpm&_PAGE=user.UpdateActivityForm&m_process_id={$processId}&searchclient_process_id={$processId}",
                "{$baseUrl}?_APPNAME=bpm&_PAGE=claim.UpdateProcess_self&m_process_id={$processId}&searchclient_process_id={$processId}",
                "{$baseUrl}?_APPNAME=bpm&_PAGE=claim.UpdateProcess&m_process_id={$processId}&searchclient_process_id={$processId}",
                "{$baseUrl}?_APPNAME=bpm&m_process_id={$processId}",
            ];

            foreach ($endpoints as $url) {
                $response = Http::withOptions([
                    'cookies' => $this->cookieJar,
                    'verify'  => false,
                    'timeout' => 30,
                ])->get($url);

                if ($response->successful()) {
                    $html = $response->body();
                    $caso = $this->parseAndSyncHtml($html, $numeroCaso ?? "CASO-BPM-{$processId}", $processId);
                    if ($caso) {
                        return $caso;
                    }
                }
            }

            $this->safeLog('warning', "[MECIP Scraper] Petición a m_process_id={$processId} no retornó datos.");
            return null;
        } catch (\Throwable $e) {
            $this->safeLog('error', "[MECIP Scraper] Excepción al extraer proceso {$processId}: " . $e->getMessage());
            throw $e;
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

        // 1. Extraer Metadatos Dinámicos del Caso/Subproceso
        $macroproceso       = $this->extractInputOrText($xpath, "//input[@name='macroproceso'] | //span[@id='lbl_macroproceso']", '');
        $proceso            = $this->extractInputOrText($xpath, "//input[@name='proceso'] | //span[@id='lbl_proceso']", '');
        $subproceso         = $this->extractInputOrText($xpath, "//input[@name='subproceso'] | //span[@id='lbl_subproceso']", '');

        // Regex para formatos de vista cy-vista-formatos y paginas BPM IPS
        if (empty($macroproceso) && preg_match('/Macrop?oroceso:\s*(?:<\/b>)?\s*([^\n\r<]+)/i', $html, $mMacro)) {
            $macroproceso = substr(trim(strip_tags($mMacro[1])), 0, 150);
        }
        if (empty($proceso) && preg_match('/Proceso:\s*(?:<\/b>)?\s*([^\n\r<]+)/i', $html, $mProc)) {
            $proceso = substr(trim(strip_tags($mProc[1])), 0, 150);
        }
        if (empty($subproceso) && preg_match('/Subproceso(?:\/Procedimiento)?:\s*(?:<\/b>)?\s*([^\n\r<]+)/i', $html, $mSub)) {
            $subproceso = substr(trim(strip_tags($mSub[1])), 0, 150);
        }

        if (empty($macroproceso)) $macroproceso = 'PROCESO DE GOBERNANZA IPS';
        if (empty($proceso)) $proceso = 'GESTIÓN ESTRATÉGICA E INSTITUCIONAL';
        if (empty($subproceso)) $subproceso = 'Expediente y Procedimiento BPM IPS #' . $numeroCaso;

        $codigoSubproceso   = $this->extractInputOrText($xpath, "//input[@name='codigo_subproceso'] | //span[@id='lbl_codigo']", 'GES-BPM-' . $numeroCaso);
        $version            = $this->extractInputOrText($xpath, "//input[@name='version'] | //span[@id='lbl_version']", '');
        if (empty($version) && preg_match('/Versi[oó]n:\s*(?:<\/b>)?\s*([^\n\r<]+)/i', $html, $mVer)) {
            $version = substr(trim(strip_tags($mVer[1])), 0, 30);
        }
        if (empty($version)) $version = '1.0';

        $responsableAnalisis = $this->extractInputOrText($xpath, "//input[@name='responsable'] | //span[@id='lbl_responsable']", '');
        if (empty($responsableAnalisis) && preg_match('/(?:Elaborado|Co-Elaborado|Realizado|Responsable)\s*por:\s*(?:<\/b>)?\s*([^\n\r,<]+)/i', $html, $mResp)) {
            $responsableAnalisis = substr(trim(strip_tags($mResp[1])), 0, 100);
        }
        if (empty($responsableAnalisis)) $responsableAnalisis = 'Analista BPM IPS';

        // 2. Extraer Tablas de Productos, Insumos, Actividades y Tareas de forma Dinámica
        $productos   = [];
        $insumos     = [];
        $actividades = [];
        $tareasMap   = [];

        $allTables = $xpath->query("//table");
        if ($allTables && $allTables->length > 0) {
            foreach ($allTables as $tblNode) {
                $headerTxt = '';
                $ths = $xpath->query(".//th", $tblNode);
                foreach ($ths as $th) {
                    $headerTxt .= ' ' . mb_strtolower(trim($th->textContent));
                }

                $rows = $xpath->query(".//tbody/tr | .//tr[position() > 1]", $tblNode);
                if (!$rows || $rows->length === 0) continue;

                // FORMATO 46: PRODUCTOS
                if (str_contains($headerTxt, 'producto') || str_contains($headerTxt, 'cliente')) {
                    foreach ($rows as $r) {
                        $tds = $xpath->query(".//td", $r);
                        if ($tds && $tds->length >= 2) {
                            $codigo = $this->extractCellText($xpath, $tds->item(0));
                            $nombre = $tds->length >= 2 ? $this->extractCellText($xpath, $tds->item(1)) : '';
                            $caract = $tds->length >= 3 ? $this->extractCellText($xpath, $tds->item(2)) : '';
                            $cliente = $tds->length >= 4 ? $this->extractCellText($xpath, $tds->item(3)) : 'Consejo de Administración / Gerencias';

                            $productos[] = [
                                'codigo'      => $codigo,
                                'nombre'      => !empty($nombre) ? $nombre : $codigo,
                                'descripcion' => !empty($caract) ? $caract : $codigo,
                                'entidad'     => $cliente,
                            ];
                        }
                    }
                }
                // FORMATO 47: INSUMOS
                elseif (str_contains($headerTxt, 'insumo') || str_contains($headerTxt, 'proveedor')) {
                    foreach ($rows as $r) {
                        $tds = $xpath->query(".//td", $r);
                        if ($tds && $tds->length >= 2) {
                            $codigo = $this->extractCellText($xpath, $tds->item(0));
                            $nombre = $tds->length >= 2 ? $this->extractCellText($xpath, $tds->item(1)) : '';
                            $caract = $tds->length >= 3 ? $this->extractCellText($xpath, $tds->item(2)) : '';
                            $proveedor = $tds->length >= 4 ? $this->extractCellText($xpath, $tds->item(3)) : 'Todas las direcciones';

                            $insumos[] = [
                                'codigo'      => $codigo,
                                'nombre'      => !empty($nombre) ? $nombre : $codigo,
                                'descripcion' => !empty($caract) ? $caract : $codigo,
                                'entidad'     => $proveedor,
                            ];
                        }
                    }
                }
                // FORMATO 48: ACTIVIDADES
                elseif (str_contains($headerTxt, 'actividad') && (str_contains($headerTxt, 'objetivo') || str_contains($headerTxt, 'responsable'))) {
                    foreach ($rows as $r) {
                        $tds = $xpath->query(".//td", $r);
                        if ($tds && $tds->length >= 2) {
                            $codigo = $this->extractCellText($xpath, $tds->item(0));
                            $nombre = $tds->length >= 2 ? $this->extractCellText($xpath, $tds->item(1)) : '';
                            $objetivo = $tds->length >= 3 ? $this->extractCellText($xpath, $tds->item(2)) : '';
                            $responsable = $tds->length >= 4 ? $this->extractCellText($xpath, $tds->item(3)) : 'Analista IPS';

                            $actividades[] = [
                                'codigo'      => $codigo,
                                'nombre'      => !empty($nombre) ? $nombre : $codigo,
                                'objetivo'    => $objetivo,
                                'responsable' => $responsable,
                            ];
                        }
                    }
                }
                // FORMATO 49 / 93: TAREAS
                elseif (str_contains($headerTxt, 'tarea') && (str_contains($headerTxt, 'tiempo') || str_contains($headerTxt, 'metodos'))) {
                    $currAct = '';
                    foreach ($rows as $r) {
                        $tds = $xpath->query(".//td", $r);
                        if ($tds) {
                            if ($tds->length >= 4) {
                                $currAct = $this->extractCellText($xpath, $tds->item(1));
                                $tDesc   = $this->extractCellText($xpath, $tds->item(2));
                                $tTime   = $this->extractCellText($xpath, $tds->item(3));
                                $tareasMap[$currAct][] = ['descripcion' => $tDesc, 'tiempo' => $tTime];
                            } elseif ($tds->length == 2 && !empty($currAct)) {
                                $tDesc = $this->extractCellText($xpath, $tds->item(0));
                                $tTime = $this->extractCellText($xpath, $tds->item(1));
                                $tareasMap[$currAct][] = ['descripcion' => $tDesc, 'tiempo' => $tTime];
                            }
                        }
                    }
                }
            }
        }

        // Si no hay actividades explícitas en tablas, generar la Actividad Principal basada en el Subproceso Real
        if (empty($actividades)) {
            $actNombre = $subproceso;
            $comentarioTxt = 'Procesamiento e inspección de expediente BPM IPS.';
            if (preg_match('/Comentario(?:s| anterior)?:\s*([^\n\r\t<]+)/i', $html, $mCom)) {
                $comentarioTxt = trim($mCom[1]);
            }

            $actividades = [
                [
                    'codigo'      => 'ACT_01',
                    'nombre'      => $actNombre,
                    'responsable' => $responsableAnalisis,
                    'objetivo'    => $comentarioTxt,
                ]
            ];
        }

        // Si productos/insumos están vacíos, generarlos a partir de la actividad real extraída (NUNCA texto estático hardcodeado)
        if (empty($productos)) {
            $productos = [
                [
                    'codigo'      => 'PROD_01',
                    'nombre'      => 'Resultado / Producto de ' . $subproceso,
                    'entidad'     => 'Destinatario del Subproceso',
                    'descripcion' => 'Documentación y entregable del expediente IPS #' . $numeroCaso,
                ]
            ];
        }

        if (empty($insumos)) {
            $insumos = [
                [
                    'codigo'      => 'INS_01',
                    'nombre'      => 'Solicitud / Entrada de ' . $subproceso,
                    'entidad'     => 'Unidad Origen IPS',
                    'descripcion' => 'Antecedentes e insumos presentados para el expediente IPS #' . $numeroCaso,
                ]
            ];
        }

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
                    'estado_flujo'         => 'borrador',
                ]
            );

            // Sincronizar Componentes (Productos e Insumos)
            $caso->componentes()->delete();

            $orden = 1;
            foreach ($productos as $p) {
                MecipCasoComponente::create([
                    'mecip_caso_id'          => $caso->id,
                    'tipo'                   => 'producto',
                    'nombre'                 => $p['nombre'] ?? 'Producto Sincronizado',
                    'entidad_origen_destino' => $p['entidad'] ?? 'Área Destino',
                    'descripcion'            => $p['descripcion'] ?? '',
                    'orden'                  => $orden++,
                ]);
            }

            foreach ($insumos as $i) {
                MecipCasoComponente::create([
                    'mecip_caso_id'          => $caso->id,
                    'tipo'                   => 'insumo',
                    'nombre'                 => $i['nombre'] ?? 'Insumo Sincronizado',
                    'entidad_origen_destino' => $i['entidad'] ?? 'Área Origen',
                    'descripcion'            => $i['descripcion'] ?? '',
                    'orden'                  => $orden++,
                ]);
            }

            // Sincronizar Actividades
            if (!empty($actividades)) {
                $caso->actividades()->delete();
                $actOrden = 1;
                foreach ($actividades as $act) {
                    $actNombreKey = $act['nombre'] ?? '';
                    $actividad = MecipCasoActividad::create([
                        'mecip_caso_id'      => $caso->id,
                        'codigo_actividad'   => $act['codigo'] ?? "ACT-{$actOrden}",
                        'nombre'             => $actNombreKey,
                        'responsable'        => $act['responsable'] ?? 'Responsable Asignado',
                        'orden'              => $actOrden++,
                    ]);

                    // Buscar tareas asociadas en $tareasMap
                    $foundTareas = [];
                    foreach ($tareasMap as $aKey => $tList) {
                        if (str_contains(mb_strtolower($aKey), mb_strtolower(substr($actNombreKey, 0, 30))) || str_contains(mb_strtolower($actNombreKey), mb_strtolower(substr($aKey, 0, 30)))) {
                            $foundTareas = $tList;
                            break;
                        }
                    }

                    if (!empty($foundTareas)) {
                        $tOrden = 1;
                        foreach ($foundTareas as $t) {
                            $timeMin = 30;
                            if (preg_match('/(\d+)\s*d[ií]as?/i', $t['tiempo'], $mTime)) {
                                $timeMin = (int)$mTime[1] * 480; // 8 horas por dia
                            } elseif (preg_match('/(\d+)\s*horas?/i', $t['tiempo'], $mTime)) {
                                $timeMin = (int)$mTime[1] * 60;
                            } elseif (preg_match('/(\d+)\s*min/i', $t['tiempo'], $mTime)) {
                                $timeMin = (int)$mTime[1];
                            }

                            MecipCasoTarea::create([
                                'actividad_id'            => $actividad->id,
                                'descripcion'             => $t['descripcion'],
                                'tiempo_estimado_minutos' => $timeMin,
                                'orden'                   => $tOrden++,
                            ]);
                        }
                    } else {
                        MecipCasoTarea::create([
                            'actividad_id'            => $actividad->id,
                            'descripcion'             => $act['objetivo'] ?? 'Ejecución y revisión del procedimiento MECIP IPS',
                            'tiempo_estimado_minutos' => 30,
                            'orden'                   => 1,
                        ]);
                    }
                }
            }

            // Obteniendo el user_id correspondiente al usuario o fallback a ID 1 (Julio Franco)
            $matchedUser = null;
            if (!empty($this->username)) {
                $matchedUser = \App\Models\User::where('email', 'like', "{$this->username}%")
                    ->orWhere('email', 'like', "%{$this->username}%")
                    ->first();
            }
            $firstUser = \App\Models\User::orderBy('id', 'asc')->first();
            $userId = auth()->id() ?? ($matchedUser ? $matchedUser->id : ($firstUser ? $firstUser->id : 1));

            // Sincronizar Comentarios e Historial del Expediente BPM
            $comentarioTxt = 'Se gestiona el expediente para revisión de modelado y aprobaciones correspondientes.';
            if (preg_match('/Comentario anterior:\s*([^\n\r\t<]+)/i', $html, $mCom)) {
                $comentarioTxt = trim($mCom[1]);
            } elseif (preg_match('/Comentarios[^:]*:\s*([^\n\r\t<]+)/i', $html, $mCom2)) {
                $comentarioTxt = trim($mCom2[1]);
            }

            MecipCasoCambio::create([
                'mecip_caso_id'   => $caso->id,
                'user_id'         => $userId,
                'estado_anterior' => 'borrador',
                'estado_nuevo'    => 'borrador',
                'tipo_cambio'     => 'SINCRONIZACION_SCRAPING_BPM',
                'resumen_cambio'  => "Sincronización automática de expediente.",
                'detalles_json'   => json_encode(['comentario' => $comentarioTxt]),
            ]);

            DB::commit();
            $this->safeLog('info', "[MECIP Scraper] Caso {$numeroCaso} sincronizado exitosamente con ID: {$caso->id}");

            return $caso;

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->safeLog('error', "[MECIP Scraper] Error al sincronizar caso {$numeroCaso} en la BD: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Extrae texto formateado de una celda DOMNode respetando divs y párrafos
     */
    protected function extractCellText(\DOMXPath $xpath, \DOMNode $node): string
    {
        $divs = $xpath->query(".//div", $node);
        if ($divs && $divs->length > 1) {
            $lines = [];
            foreach ($divs as $d) {
                $txt = trim(preg_replace('/\s+/', ' ', strip_tags($d->textContent)));
                if (!empty($txt)) $lines[] = $txt;
            }
            return implode("\n", $lines);
        }
        return trim(preg_replace('/\s+/', ' ', strip_tags($node->textContent)));
    }

    /**
     * Extrae el valor de un input o elemento de texto vía XPath
     */
    protected function extractInputOrText(\DOMXPath $xpath, string $expression, string $default = ''): string
    {
        $nodes = $xpath->query($expression);
        if ($nodes && $nodes->length > 0) {
            $node = $nodes->item(0);
            $val = '';
            if ($node->hasAttribute('value')) {
                $val = trim($node->getAttribute('value'));
            } else {
                $val = trim($node->textContent);
            }
            if (!empty($val)) {
                $val = trim(preg_replace('/\s+/', ' ', strip_tags($val)));
                if (strlen($val) > 150) {
                    $val = substr($val, 0, 150);
                }
                return $val;
            }
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

    /**
     * Extrae filas directamente de un nodo DOMTable especifico
     */
    protected function extractTableRowsFromNode(\DOMXPath $xpath, \DOMNode $tableNode): array
    {
        $rowsData = [];
        $rows = $xpath->query(".//tr[position() > 1]", $tableNode);

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
                if (!empty($rowData['nombre'])) {
                    $rowsData[] = $rowData;
                }
            }
        }

        return $rowsData;
    }
}
