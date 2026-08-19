<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NovedadesController extends Controller
{
    /**
     * Muestra la vista principal del Historial de Novedades y Desarrollos del Sistema
     */
    public function index(Request $request)
    {
        $commits = $this->obtenerHistorialCommits(150);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'data'    => $commits,
                'total'   => count($commits),
            ]);
        }

        return view('admin.novedades.index', compact('commits'));
    }

    /**
     * Lee y parsea el historial de commits desde el repositorio Git local
     */
    private function obtenerHistorialCommits(int $limit = 150): array
    {
        $commits = [];
        try {
            // Ejecuta git log con delimitadores seguros: hash|||author|||email|||date|||subject
            $command = "git log -n {$limit} --pretty=format:\"%h|||%an|||%ae|||%cd|||%s\" --date=format:\"%d/%m/%Y %H:%M\"";
            $output = shell_exec($command);

            if ($output) {
                $lines = explode("\n", trim($output));
                foreach ($lines as $line) {
                    $parts = explode("|||", $line);
                    if (count($parts) >= 5) {
                        $hash    = trim($parts[0]);
                        $author  = trim($parts[1]);
                        $email   = trim($parts[2]);
                        $date    = trim($parts[3]);
                        $subject = trim($parts[4]);

                        // Determinar categoría y badge según convención convencional (feat, fix, style, docs, etc.)
                        $categoria = 'MEJORA GENERAL';
                        $badgeClass = 'badge-primary';
                        $icon = 'fa-rocket';

                        $subLower = strtolower($subject);
                        if (str_starts_with($subLower, 'feat') || str_contains($subLower, 'mejora') || str_contains($subLower, 'nuevo') || str_contains($subLower, 'generador')) {
                            $categoria = '🚀 NUEVA FUNCIONALIDAD';
                            $badgeClass = 'badge-success';
                            $icon = 'fa-magic';
                        } elseif (str_starts_with($subLower, 'fix') || str_contains($subLower, 'corrig') || str_contains($subLower, 'solucion') || str_contains($subLower, 'ajuste')) {
                            $categoria = '🛠️ CORRECCIÓN / BUGFIX';
                            $badgeClass = 'badge-warning text-dark';
                            $icon = 'fa-bug';
                        } elseif (str_starts_with($subLower, 'style') || str_contains($subLower, 'diseño') || str_contains($subLower, 'ui') || str_contains($subLower, 'rediseño')) {
                            $categoria = '🎨 DISEÑO & UI';
                            $badgeClass = 'badge-info';
                            $icon = 'fa-palette';
                        } elseif (str_starts_with($subLower, 'docs')) {
                            $categoria = '📄 DOCUMENTACIÓN';
                            $badgeClass = 'badge-secondary';
                            $icon = 'fa-book';
                        }

                        // Formatear avatar del autor
                        $iniciales = strtoupper(substr($author, 0, 2));

                        $commits[] = [
                            'hash'        => $hash,
                            'author'      => $author,
                            'email'       => $email,
                            'iniciales'   => $iniciales,
                            'date'        => $date,
                            'subject'     => $subject,
                            'categoria'   => $categoria,
                            'badge_class' => $badgeClass,
                            'icon'        => $icon,
                            'github_url'  => "https://github.com/jufrancopy/sistem_postgres_pei/commit/{$hash}",
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Error leyendo historial Git: ' . $e->getMessage());
        }

        return $commits;
    }
}
