<?php

namespace Tests\Unit;

use App\Application\Bioestadistica\Audit\AuditRedactor;
use App\Models\Bioestadistica\HospEpisodio;
use App\Models\Bioestadistica\ImportJob;
use App\Models\Bioestadistica\RecordValue;
use PHPUnit\Framework\TestCase;

class AuditRedactorTest extends TestCase
{
    public function test_redacts_cedula_hash_and_clinical_fields(): void
    {
        $redactor = new AuditRedactor();
        $payload = $redactor->redact([
            'cedula' => '1234567',
            'cedula_hash' => 'abc',
            'diagnostico' => 'Neumonía',
            'sexo' => 'F',
            'servicio' => 'UTI',
        ], new HospEpisodio());

        $this->assertSame(['redacted' => true, 'changed' => true], $payload['cedula']);
        $this->assertSame(['redacted' => true, 'changed' => true], $payload['cedula_hash']);
        $this->assertSame(['redacted' => true, 'changed' => true], $payload['diagnostico']);
        $this->assertSame('F', $payload['sexo']);
        $this->assertSame('UTI', $payload['servicio']);
        $this->assertStringNotContainsString('1234567', json_encode($payload));
        $this->assertStringNotContainsString('Neumonía', json_encode($payload));
    }

    public function test_record_value_text_date_and_json_are_sensitive(): void
    {
        $redactor = new AuditRedactor();
        $payload = $redactor->redact([
            'value_text' => 'secreto',
            'value_date' => '2026-01-01',
            'value_json' => ['nota' => 'clínica'],
            'value_num' => 12.5,
            'value_bool' => true,
        ], new RecordValue());

        $this->assertTrue($payload['value_text']['redacted']);
        $this->assertTrue($payload['value_date']['redacted']);
        $this->assertTrue($payload['value_json']['redacted']);
        $this->assertSame(12.5, $payload['value_num']);
        $this->assertTrue($payload['value_bool']);
    }

    public function test_import_paths_are_redacted(): void
    {
        $redactor = new AuditRedactor();
        $payload = $redactor->redact([
            'archivo_path' => 'bioestadistica/imports/secret.xlsx',
            'checksum' => 'deadbeef',
            'estado' => 'completado',
        ], new ImportJob());

        $this->assertTrue($payload['archivo_path']['redacted']);
        $this->assertTrue($payload['checksum']['redacted']);
        $this->assertSame('completado', $payload['estado']);
        $this->assertStringNotContainsString('secret.xlsx', json_encode($payload));
    }
}
