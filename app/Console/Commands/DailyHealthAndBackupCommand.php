<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DailyHealthAndBackupCommand extends Command
{
    /**
     * El nombre y firma del comando Artisan.
     *
     * @var string
     */
    protected $signature = 'siplan:health-and-backup
                            {--email=jucfra23@gmail.com : Correo receptor del reporte}
                            {--skip-mail : Omitir envío de correo}
                            {--skip-backup : Omitir respaldo de base de datos}';

    /**
     * La descripción del comando.
     *
     * @var string
     */
    protected $description = 'Realiza el respaldo diario de PostgreSQL en gzip y envía el reporte de salud del servidor a jucfra23@gmail.com';

    public function handle(): int
    {
        $emailRecipient = $this->option('email');
        $skipMail       = $this->option('skip-mail');
        $skipBackup     = $this->option('skip-backup');

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("  🏥  SIPLAN — RESPALDO Y DIAGNÓSTICO DE SALUD (" . now()->format('Y-m-d H:i:s') . ")");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        $reportData = [
            'timestamp'     => now()->format('d/m/Y H:i:s'),
            'environment'   => config('app.env'),
            'app_url'       => config('app.url'),
            'backup'        => null,
            'services'      => [],
            'resources'     => [],
            'logs'          => [],
            'status'        => 'SUCCESS', // SUCCESS, WARNING, DANGER
            'errors'        => 0,
            'warnings'      => 0,
        ];

        // ─────────────────────────────────────────────────────────────────────
        // 1. RESPALDO DE BASE DE DATOS POSTGRESQL
        // ─────────────────────────────────────────────────────────────────────
        if (!$skipBackup) {
            $this->info("\n▶ [1/4] Generando Respaldo de Base de Datos PostgreSQL...");
            $backupResult = $this->performDatabaseBackup();
            $reportData['backup'] = $backupResult;

            if (!$backupResult['success']) {
                $reportData['errors']++;
                $reportData['status'] = 'DANGER';
            }
        }

        // ─────────────────────────────────────────────────────────────────────
        // 2. DIAGNÓSTICO DE BASE DE DATOS Y REDIS
        // ─────────────────────────────────────────────────────────────────────
        $this->info("\n▶ [2/4] Verificando Servicios de Base de Datos y Redis...");
        
        // PostgreSQL
        try {
            DB::connection()->getPdo();
            $reportData['services']['postgresql'] = ['status' => 'OK', 'message' => 'Conexión PDO activa'];
            $this->info("  [✔] PostgreSQL: CONECTADO (OK)");
        } catch (\Exception $e) {
            $reportData['services']['postgresql'] = ['status' => 'ERROR', 'message' => $e->getMessage()];
            $this->error("  [❌] PostgreSQL: FALLÓ (" . $e->getMessage() . ")");
            $reportData['errors']++;
        }

        // Redis
        try {
            $pong = Redis::ping();
            $reportData['services']['redis'] = ['status' => 'OK', 'message' => "Respuesta: $pong"];
            $this->info("  [✔] Redis: PONG (OK)");
        } catch (\Exception $e) {
            $reportData['services']['redis'] = ['status' => 'ERROR', 'message' => $e->getMessage()];
            $this->error("  [❌] Redis: FALLÓ (" . $e->getMessage() . ")");
            $reportData['errors']++;
        }

        // ─────────────────────────────────────────────────────────────────────
        // 3. EVALUACIÓN DE DISCO Y LOGS DE ERRORES
        // ─────────────────────────────────────────────────────────────────────
        $this->info("\n▶ [3/4] Evaluando Recursos y Logs de Laravel...");

        // Disco
        $storagePath = storage_path();
        $freeSpace   = @disk_free_space($storagePath);
        $totalSpace  = @disk_total_space($storagePath);

        if ($freeSpace !== false && $totalSpace !== false) {
            $usedPercent = round((($totalSpace - $freeSpace) / $totalSpace) * 100, 1);
            $freeGB      = round($freeSpace / (1024 * 1024 * 1024), 2);
            $totalGB     = round($totalSpace / (1024 * 1024 * 1024), 2);

            $reportData['resources']['disk'] = [
                'used_percent' => $usedPercent,
                'free_gb'      => $freeGB,
                'total_gb'     => $totalGB,
                'status'       => $usedPercent > 85 ? 'DANGER' : ($usedPercent > 70 ? 'WARNING' : 'OK')
            ];

            if ($usedPercent > 85) {
                $reportData['errors']++;
                $this->error("  [❌] Disco duro: {$usedPercent}% utilizado ({$freeGB} GB libres)");
            } else {
                $this->info("  [✔] Disco duro: {$usedPercent}% utilizado ({$freeGB} GB libres de {$totalGB} GB)");
            }
        }

        // Logs de Laravel de hoy
        $logPath   = storage_path('logs/laravel.log');
        $todayStr  = now()->format('Y-m-d');
        $todayErrors = 0;

        if (file_exists($logPath)) {
            $handle = @fopen($logPath, "r");
            if ($handle) {
                while (($line = fgets($handle)) !== false) {
                    if (str_contains($line, $todayStr) && (str_contains($line, '.ERROR') || str_contains($line, '.CRITICAL') || str_contains($line, '.EMERGENCY'))) {
                        $todayErrors++;
                    }
                }
                fclose($handle);
            }
        }

        $reportData['logs'] = [
            'today_errors' => $todayErrors,
            'status'       => $todayErrors > 0 ? 'WARNING' : 'OK'
        ];

        if ($todayErrors > 0) {
            $reportData['warnings']++;
            $this->warn("  [⚠️] Logs de Laravel: {$todayErrors} error(es) registrados hoy.");
        } else {
            $this->info("  [✔] Logs de Laravel: 0 errores registrados hoy.");
        }

        // Determinar estado final
        if ($reportData['errors'] > 0) {
            $reportData['status'] = 'DANGER';
        } elseif ($reportData['warnings'] > 0) {
            $reportData['status'] = 'WARNING';
        }

        // ─────────────────────────────────────────────────────────────────────
        // 4. ENVÍO DE CORREO ELECTRÓNICO CON EL REPORTE
        // ─────────────────────────────────────────────────────────────────────
        if (!$skipMail && $emailRecipient) {
            $this->info("\n▶ [4/4] Enviando Reporte por Correo Electrónico a $emailRecipient...");
            $this->sendReportEmail($emailRecipient, $reportData);
        }

        $this->info("\n━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("  ✔ Diagnóstico y Respaldo completados con éxito.");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n");

        return self::SUCCESS;
    }

    /**
     * Genera un dump de la base de datos PostgreSQL comprimido en .sql.gz
     * y elimina respaldos antiguos (> 14 días).
     */
    private function performDatabaseBackup(): array
    {
        $connectionName = config('database.default');
        $dbConfig       = config("database.connections.{$connectionName}");

        if (!$dbConfig || $dbConfig['driver'] !== 'pgsql') {
            return [
                'success' => false,
                'message' => 'El driver de la base de datos no es PostgreSQL'
            ];
        }

        $backupDir = storage_path('app/backups');
        if (!file_exists($backupDir)) {
            @mkdir($backupDir, 0775, true);
        }

        $filename   = 'siplan_db_' . now()->format('Y-m-d_H-i-s') . '.sql.gz';
        $targetFile = $backupDir . '/' . $filename;

        $dbHost = $dbConfig['host'] ?? '127.0.0.1';
        $dbPort = $dbConfig['port'] ?? 5432;
        $dbName = $dbConfig['database'];
        $dbUser = $dbConfig['username'];
        $dbPass = $dbConfig['password'] ?? '';

        // Buscar la ruta ejecutable de pg_dump en Linux y macOS (Postgres.app / Homebrew / Apt)
        $pgDumpBinary = 'pg_dump';
        $possiblePaths = [
            '/usr/bin/pg_dump',
            '/usr/local/bin/pg_dump',
            '/opt/homebrew/bin/pg_dump',
            '/Applications/Postgres.app/Contents/Versions/latest/bin/pg_dump',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path) && is_executable($path)) {
                $pgDumpBinary = $path;
                break;
            }
        }

        // Comando pg_dump comprimido
        $cmd = sprintf(
            'PGPASSWORD=%s %s -h %s -p %s -U %s %s | gzip > %s 2>&1',
            escapeshellarg($dbPass),
            escapeshellarg($pgDumpBinary),
            escapeshellarg($dbHost),
            escapeshellarg($dbPort),
            escapeshellarg($dbUser),
            escapeshellarg($dbName),
            escapeshellarg($targetFile)
        );

        exec($cmd, $output, $returnVar);

        if ($returnVar === 0 && file_exists($targetFile) && filesize($targetFile) > 100) {
            $sizeMB = round(filesize($targetFile) / (1024 * 1024), 2);
            $this->info("  [✔] Respaldo generado: $filename ($sizeMB MB)");

            // Limpieza de respaldos antiguos (> 14 días)
            $deletedOld = 0;
            foreach (glob($backupDir . '/*.sql.gz') as $oldFile) {
                if (filemtime($oldFile) < strtotime('-14 days')) {
                    @unlink($oldFile);
                    $deletedOld++;
                }
            }
            if ($deletedOld > 0) {
                $this->info("  [ℹ] Se eliminaron $deletedOld respaldo(s) antiguo(s) (>14 días).");
            }

            return [
                'success'   => true,
                'filename'  => $filename,
                'size_mb'   => $sizeMB,
                'path'      => $targetFile,
                'created_at'=> now()->format('d/m/Y H:i:s')
            ];
        }

        if (file_exists($targetFile)) {
            @unlink($targetFile);
        }

        $errorMessage = implode("\n", $output);
        $this->error("  [❌] Falló el respaldo de PostgreSQL: " . ($errorMessage ?: 'pg_dump error'));

        return [
            'success' => false,
            'message' => $errorMessage ?: 'Fallo al ejecutar pg_dump o base de datos sin datos.'
        ];
    }

    /**
     * Construye y envía el correo HTML con el resumen del diagnóstico.
     */
    private function sendReportEmail(string $recipient, array $data): void
    {
        $statusColor = match ($data['status']) {
            'SUCCESS' => '#28a745',
            'WARNING' => '#ffc107',
            'DANGER'  => '#dc3545',
            default   => '#6c757d'
        };

        $statusText = match ($data['status']) {
            'SUCCESS' => '🟢 SALUDABLE — Todos los sistemas operativos',
            'WARNING' => '🟡 ADVERTENCIAS DETECTADAS',
            'DANGER'  => '🔴 ALERTA DE SISTEMA — Revisar de inmediato',
            default   => 'DIAGNÓSTICO'
        };

        $backupHtml = '';
        if ($data['backup']) {
            if ($data['backup']['success']) {
                $backupHtml = "
                <div style='background:#f8f9fa; border-left: 4px solid #28a745; padding: 12px; margin-bottom: 15px;'>
                    <strong style='color:#28a745;'>✔ Respaldo de Base de Datos Generado con Éxito</strong><br>
                    <small>Archivo: <code>{$data['backup']['filename']}</code></small><br>
                    <small>Tamaño: <strong>{$data['backup']['size_mb']} MB</strong> (Comprimido .sql.gz)</small><br>
                    <small>Fecha: {$data['backup']['created_at']}</small>
                </div>";
            } else {
                $backupHtml = "
                <div style='background:#fff3f3; border-left: 4px solid #dc3545; padding: 12px; margin-bottom: 15px;'>
                    <strong style='color:#dc3545;'>❌ Fallo en Respaldo de Base de Datos</strong><br>
                    <small style='color:#666;'>Error: {$data['backup']['message']}</small>
                </div>";
            }
        }

        $diskInfo = isset($data['resources']['disk'])
            ? "{$data['resources']['disk']['used_percent']}% en uso ({$data['resources']['disk']['free_gb']} GB libres de {$data['resources']['disk']['total_gb']} GB)"
            : "No disponible";

        $html = "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='utf-8'>
            <style>
                body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
                .header { background: {$statusColor}; padding: 20px; text-align: center; color: #ffffff; }
                .header h2 { margin: 0; font-size: 20px; }
                .content { padding: 25px; }
                .section-title { font-size: 14px; text-transform: uppercase; color: #6c757d; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-top: 20px; margin-bottom: 12px; font-weight: bold; }
                .badge { display: inline-block; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; color: #fff; background: #28a745; }
                .badge-danger { background: #dc3545; }
                .footer { background: #f8f9fa; text-align: center; padding: 15px; font-size: 12px; color: #6c757d; border-top: 1px solid #eee; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>SIPLAN — Reporte Diario de Salud</h2>
                    <p style='margin: 5px 0 0 0; font-size: 13px;'>{$data['timestamp']} | Servidor: {$data['app_url']}</p>
                </div>

                <div class='content'>
                    <div style='text-align:center; padding: 10px; background:#f0f4f8; border-radius:6px; font-weight:bold; color:{$statusColor}; margin-bottom: 20px;'>
                        {$statusText}
                    </div>

                    <div class='section-title'>1. Respaldo de Base de Datos</div>
                    {$backupHtml}

                    <div class='section-title'>2. Estado de Servicios y Conexiones</div>
                    <table width='100%' cellpadding='6' cellspacing='0' style='font-size:13px;'>
                        <tr>
                            <td><strong>PostgreSQL Database:</strong></td>
                            <td align='right'><span class='badge'>" . ($data['services']['postgresql']['status'] ?? 'N/A') . "</span></td>
                        </tr>
                        <tr>
                            <td><strong>Redis Daemon & Cache:</strong></td>
                            <td align='right'><span class='badge'>" . ($data['services']['redis']['status'] ?? 'N/A') . "</span></td>
                        </tr>
                    </table>

                    <div class='section-title'>3. Recursos del Servidor</div>
                    <table width='100%' cellpadding='6' cellspacing='0' style='font-size:13px;'>
                        <tr>
                            <td><strong>Almacenamiento en Disco:</strong></td>
                            <td align='right'>{$diskInfo}</td>
                        </tr>
                    </table>

                    <div class='section-title'>4. Registro de Errores (Laravel Logs)</div>
                    <p style='font-size:13px; margin: 5px 0;'>
                        Errores o excepciones registradas hoy: <strong>{$data['logs']['today_errors']}</strong>
                    </p>
                </div>

                <div class='footer'>
                    SIPLAN — Sistema de Planificación Estratégica Institucional<br>
                    Generado automáticamente por el servidor el {$data['timestamp']}
                </div>
            </div>
        </body>
        </html>";

        try {
            Mail::html($html, function ($message) use ($recipient, $data) {
                $subjectTag = match ($data['status']) {
                    'SUCCESS' => '🟢 [OK]',
                    'WARNING' => '🟡 [ADVERTENCIA]',
                    'DANGER'  => '🔴 [ALERTA]',
                    default   => '[REPORTE]'
                };

                $message->to($recipient)
                        ->subject("$subjectTag SIPLAN — Respaldo DB & Diagnóstico Diario (" . now()->format('d/m/Y') . ")");
            });
            $this->info("  [✔] Correo enviado exitosamente a: $recipient");
        } catch (\Exception $e) {
            $this->error("  [❌] Falló el envío del correo: " . $e->getMessage());
            Log::error("Fallo al enviar correo de salud SIPLAN: " . $e->getMessage());
        }
    }
}
