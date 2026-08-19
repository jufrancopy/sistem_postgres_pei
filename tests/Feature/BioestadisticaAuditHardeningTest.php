<?php

namespace Tests\Feature;

use App\Application\Bioestadistica\Audit\AuditService;
use App\Application\Bioestadistica\Indicators\IndicatorCacheService;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Bioestadistica\AuditLog;
use App\Models\Bioestadistica\Variable;
use App\Models\Bioestadistica\Dashboard;
use App\Models\Bioestadistica\Establecimiento;
use App\Models\Bioestadistica\Formulario;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\Indicador;
use App\Models\Bioestadistica\IndicadorCache;
use App\Models\Bioestadistica\Record;
use App\Models\Bioestadistica\UsuarioEstablecimiento;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Tests\TestCase;

class BioestadisticaAuditHardeningTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        if (! Schema::connection('pgsql')->hasTable('bioestadistica.audit_log')) {
            $this->markTestSkipped('Falta la tabla bioestadistica.audit_log. Ejecute las migraciones de F7.');
        }
    }

    public function test_observer_stores_dirty_old_and_new_only(): void
    {
        $user = $this->userWithRole('Administrador');
        Auth::login($user);
        $original = 'Original '.Str::random(8);
        $variable = Variable::create([
            'codigo' => 'F7',
            'nombre' => $original,
            'activo' => true,
        ]);
        $variable->update(['nombre' => 'Actualizado']);

        $log = AuditLog::query()
            ->where('entity_type', Variable::class)
            ->where('entity_id', $variable->id)
            ->where('accion', 'update')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $this->assertArrayHasKey('nombre', $log->old_values);
        $this->assertArrayHasKey('nombre', $log->new_values);
        $this->assertSame($original, $log->old_values['nombre']);
        $this->assertSame('Actualizado', $log->new_values['nombre']);
        $this->assertArrayNotHasKey('descripcion', $log->old_values);
        $this->assertArrayNotHasKey('updated_at', $log->old_values);
    }

    public function test_hosp_episodio_redacts_pii_and_does_not_log_cedula(): void
    {
        $user = $this->userWithRole('Administrador');
        $establecimiento = Establecimiento::first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan usuario o establecimiento.');
        }
        Auth::login($user);

        $episodio = HospEpisodio::create([
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 2026,
            'periodo_mes' => 8,
            'cedula' => '1234567',
            'cedula_hash' => HospEpisodio::hashCedula('1234567'),
            'sexo' => 'F',
            'fecha_ingreso' => '2026-08-01',
            'servicio' => 'UTI',
            'diagnostico' => 'Neumonía atípica',
            'cie10' => 'J18.9',
        ]);

        $log = AuditLog::query()
            ->where('entity_type', HospEpisodio::class)
            ->where('entity_id', $episodio->id)
            ->where('accion', 'create')
            ->latest('id')
            ->first();

        $this->assertNotNull($log);
        $encoded = json_encode($log->toArray());
        $this->assertStringNotContainsString('1234567', $encoded);
        $this->assertStringNotContainsString('Neumonía', $encoded);
        $this->assertStringNotContainsString($episodio->cedula_hash, $encoded);
        $this->assertTrue($log->new_values['cedula']['redacted'] ?? false);
        $this->assertTrue($log->new_values['diagnostico']['redacted'] ?? false);
        $this->assertSame('F', $log->new_values['sexo'] ?? null);
    }

    public function test_audit_log_is_append_only(): void
    {
        $log = AuditLog::create([
            'accion' => 'view',
            'entity_type' => Variable::class,
            'entity_id' => 1,
            'created_at' => now(),
        ]);

        $this->expectException(\LogicException::class);
        $log->update(['accion' => 'export']);
    }

    public function test_postgres_trigger_rejects_update_and_delete(): void
    {
        $id = AuditLog::create([
            'accion' => 'view',
            'entity_type' => Variable::class,
            'entity_id' => 1,
            'created_at' => now(),
        ])->id;

        $db = DB::connection('pgsql');
        $db->unprepared('SAVEPOINT f7_audit_update');
        try {
            $db->table('bioestadistica.audit_log')->where('id', $id)->update(['accion' => 'export']);
            $this->fail('Se esperaba que el trigger rechazara el UPDATE.');
        } catch (\Throwable $exception) {
            $this->assertStringContainsString('append-only', $exception->getMessage());
            $db->unprepared('ROLLBACK TO SAVEPOINT f7_audit_update');
        }

        $db->unprepared('SAVEPOINT f7_audit_delete');
        try {
            $db->table('bioestadistica.audit_log')->where('id', $id)->delete();
            $this->fail('Se esperaba que el trigger rechazara el DELETE.');
        } catch (\Throwable $exception) {
            $this->assertStringContainsString('append-only', $exception->getMessage());
            $db->unprepared('ROLLBACK TO SAVEPOINT f7_audit_delete');
        }
    }

    public function test_record_submit_is_logged_as_submit(): void
    {
        $user = $this->userWithRole('Administrador');
        $formulario = Formulario::where('estado', 'activo')->where('layout_type', '!=', 'nominativo')->first()
            ?? Formulario::where('estado', 'activo')->first();
        $establecimiento = Establecimiento::first();
        if (! $user || ! $formulario || ! $establecimiento) {
            $this->markTestSkipped('Faltan formulario o establecimiento para probar submit.');
        }
        Auth::login($user);
        $record = Record::create([
            'formulario_id' => $formulario->id,
            'establecimiento_id' => $establecimiento->id,
            'periodo_anio' => 1991,
            'periodo_mes' => 1,
            'estado' => Record::ESTADO_BORRADOR,
            'created_by' => $user->id,
        ]);
        $record->submit($user->id);

        $log = AuditLog::query()
            ->where('entity_type', Record::class)
            ->where('entity_id', $record->id)
            ->where('accion', 'submit')
            ->latest('id')
            ->first();
        $this->assertNotNull($log);
        $this->assertSame(Record::ESTADO_ENVIADO, $log->new_values['estado'] ?? null);
    }

    public function test_form_publish_is_logged_as_publish(): void
    {
        $user = $this->userWithRole('Administrador');
        Auth::login($user);
        $formulario = Formulario::create([
            'codigo' => 'F7P'.Str::upper(Str::random(4)),
            'nombre' => 'Formulario F7',
            'estado' => 'borrador',
            'version' => 1,
        ]);
        $formulario->update(['estado' => 'activo']);

        $log = AuditLog::query()
            ->where('entity_type', Formulario::class)
            ->where('entity_id', $formulario->id)
            ->where('accion', 'publish')
            ->latest('id')
            ->first();
        $this->assertNotNull($log);
    }

    public function test_nominative_view_and_export_are_logged_explicitly(): void
    {
        $user = $this->userWithRole('Administrador');
        $establecimiento = Establecimiento::first();
        $episodio = HospEpisodio::first();
        if (! $user || ! $establecimiento) {
            $this->markTestSkipped('Faltan datos hospitalarios.');
        }
        if (! $episodio) {
            Auth::login($user);
            $episodio = HospEpisodio::create([
                'establecimiento_id' => $establecimiento->id,
                'periodo_anio' => 2026,
                'periodo_mes' => 8,
                'fecha_ingreso' => '2026-08-02',
                'sexo' => 'M',
            ]);
        }

        $this->actingAs($user)
            ->get(route('bioestadistica.hospitalizacion.edit', $episodio))
            ->assertOk();

        $this->assertTrue(
            AuditLog::query()
                ->where('accion', 'view')
                ->where('entity_type', HospEpisodio::class)
                ->where('entity_id', $episodio->id)
                ->exists()
        );

        $this->actingAs($user)
            ->get(route('bioestadistica.hospitalizacion.export'))
            ->assertOk();

        $export = AuditLog::query()
            ->where('accion', 'export')
            ->where('entity_type', HospEpisodio::class)
            ->latest('id')
            ->first();
        $this->assertNotNull($export);
        $this->assertArrayHasKey('counts', $export->new_values ?? []);
        $this->assertStringNotContainsString('1234567', json_encode($export->new_values));
        $this->assertStringNotContainsString('Neumonía', json_encode($export->new_values));
    }

    public function test_auditor_can_open_audit_ui_and_digitador_gets_403(): void
    {
        $auditor = $this->freshUserWithRole('Auditor Bioestadística');
        $digitador = $this->freshUserWithRole('Digitador Bioestadística');

        $this->actingAs($auditor)
            ->get(route('bioestadistica.auditoria.index'))
            ->assertOk();

        $this->actingAs($digitador)
            ->get(route('bioestadistica.auditoria.index'))
            ->assertForbidden();
    }

    public function test_digitador_cannot_view_record_outside_assignment(): void
    {
        $digitador = $this->freshUserWithRole('Digitador Bioestadística');
        $record = Record::first();
        if (! $record) {
            $this->markTestSkipped('No hay registros para probar alcance.');
        }
        UsuarioEstablecimiento::where('user_id', $digitador->id)->delete();

        $this->actingAs($digitador)
            ->get(route('bioestadistica.captura.edit', $record))
            ->assertForbidden();
    }

    public function test_personal_dashboard_cannot_be_edited_by_another_user(): void
    {
        $owner = $this->freshUserWithRole('Auditor Bioestadística');
        $other = $this->freshUserWithRole('Consultor Bioestadística');
        $dashboard = Dashboard::create([
            'codigo' => 'f7-'.Str::lower(Str::random(6)),
            'nombre' => 'Personal F7',
            'user_id' => $owner->id,
            'es_default' => false,
        ]);

        $this->assertFalse($other->can('update', $dashboard));

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
        $this->actingAs($other)
            ->put(route('bioestadistica.dashboards.update', $dashboard), [
                'codigo' => $dashboard->codigo,
                'nombre' => 'Hack',
            ])
            ->assertForbidden();
    }

    public function test_analista_login_redirects_to_bioestadistica_dashboard(): void
    {
        $user = $this->freshUserWithRole('Analista de Bioestadística');
        $response = (new LoginController())->authenticated(request(), $user);

        $this->assertSame(route('bioestadistica.dashboard'), $response->getTargetUrl());
    }

    public function test_admin_login_redirect_is_unchanged(): void
    {
        $user = $this->userWithRole('Administrador');
        $response = (new LoginController())->authenticated(request(), $user);

        $this->assertSame(route('planificacion-dashboard'), $response->getTargetUrl());
    }

    public function test_indicator_cache_invalidation_is_transitive(): void
    {
        $base = Indicador::activos()->whereHas('formulas')->first();
        $establecimientoId = Establecimiento::query()->value('id');
        if (! $base || ! $establecimientoId) {
            $this->markTestSkipped('No hay indicador con fórmula o establecimiento para invalidación transitiva.');
        }

        $dependent = Indicador::create([
            'codigo' => 'F7_DEP_'.Str::upper(Str::random(4)),
            'nombre' => 'Dependiente F7',
            'unidad' => 'n',
            'ambito' => 'establecimiento',
            'decimales' => 0,
            'activo' => true,
        ]);
        $dependentFormula = $dependent->formulas()->create([
            'expresion' => ['indicator' => $base->codigo],
        ]);

        IndicadorCache::create([
            'indicador_id' => $base->id,
            'formula_id' => $base->formulas()->first()->id,
            'establecimiento_id' => $establecimientoId,
            'periodo_anio' => 1992,
            'periodo_mes' => 2,
            'valor' => 1,
            'calculado_at' => now(),
        ]);
        IndicadorCache::create([
            'indicador_id' => $dependent->id,
            'formula_id' => $dependentFormula->id,
            'establecimiento_id' => $establecimientoId,
            'periodo_anio' => 1992,
            'periodo_mes' => 2,
            'valor' => 2,
            'calculado_at' => now(),
        ]);

        $deleted = app(IndicatorCacheService::class)->invalidateForIndicator($base);
        $this->assertGreaterThanOrEqual(2, $deleted);
        $this->assertFalse(IndicadorCache::where('indicador_id', $base->id)->exists());
        $this->assertFalse(IndicadorCache::where('indicador_id', $dependent->id)->exists());
    }

    public function test_muted_batch_does_not_serialize_payload(): void
    {
        $user = $this->userWithRole('Administrador');
        Auth::login($user);
        $variable = null;
        app(AuditService::class)->withoutAuditing(function () use (&$variable) {
            $variable = Variable::create([
                'codigo' => 'F7M',
                'nombre' => 'Mute '.Str::random(8),
                'activo' => true,
            ]);
        });
        $this->assertFalse(
            AuditLog::query()->where('entity_type', Variable::class)->where('entity_id', $variable->id)->exists()
        );

        app(AuditService::class)->recordBatch('import', Variable::class, (int) $variable->id, [
            'registros' => 3,
        ], ['establecimiento_id' => 1], ['phase' => 'commit']);

        $log = AuditLog::query()
            ->where('accion', 'import')
            ->where('entity_id', $variable->id)
            ->latest('id')
            ->first();
        $this->assertNotNull($log);
        $this->assertSame(3, $log->new_values['counts']['registros'] ?? null);
        $this->assertSame('commit', $log->metadata['phase'] ?? null);
    }

    private function userWithRole(string $role): User
    {
        $user = User::role($role)->first();
        if ($user) {
            return $user;
        }

        return $this->freshUserWithRole($role);
    }

    private function freshUserWithRole(string $role): User
    {
        $user = User::create([
            'name' => $role.' F7',
            'email' => 'f7-'.Str::uuid().'@example.test',
            'password' => bcrypt('secret'),
        ]);
        $user->assignRole($role);

        return $user->fresh();
    }
}
