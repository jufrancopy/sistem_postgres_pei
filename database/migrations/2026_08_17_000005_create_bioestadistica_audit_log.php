<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('pgsql')->create('bioestadistica.audit_log', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('accion', 30);
            $table->string('entity_type', 120);
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->jsonb('old_values')->nullable();
            $table->jsonb('new_values')->nullable();
            $table->ipAddress('ip')->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        $db = DB::connection('pgsql');
        $db->statement(
            "ALTER TABLE bioestadistica.audit_log
             ADD CONSTRAINT bio_audit_log_accion_check
             CHECK (accion IN (
                'create','update','delete','submit','approve','reject',
                'publish','view','export','import'
             ))"
        );
        $db->statement(
            'CREATE INDEX bio_audit_log_entity_idx
             ON bioestadistica.audit_log (entity_type, entity_id, created_at DESC)'
        );
        $db->statement(
            'CREATE INDEX bio_audit_log_user_idx
             ON bioestadistica.audit_log (user_id, created_at DESC)'
        );
        $db->statement(
            'CREATE INDEX bio_audit_log_accion_idx
             ON bioestadistica.audit_log (accion, created_at DESC)'
        );
        $db->statement(
            'CREATE INDEX bio_audit_log_created_idx
             ON bioestadistica.audit_log (created_at DESC)'
        );
        $db->statement(
            "COMMENT ON TABLE bioestadistica.audit_log IS
             'Append-only. Retención >24 meses es proceso administrativo, nunca una acción web.'"
        );

        $db->statement(<<<'SQL'
CREATE OR REPLACE FUNCTION bioestadistica.audit_log_reject_mutation()
RETURNS trigger
LANGUAGE plpgsql
AS $$
BEGIN
    RAISE EXCEPTION 'bioestadistica.audit_log is append-only';
END;
$$
SQL
        );

        $execute = $this->triggerExecuteKeyword($db);
        $db->statement(
            "CREATE TRIGGER bio_audit_log_no_update
             BEFORE UPDATE OR DELETE ON bioestadistica.audit_log
             FOR EACH ROW {$execute} bioestadistica.audit_log_reject_mutation()"
        );
    }

    public function down(): void
    {
        $db = DB::connection('pgsql');
        $db->statement('DROP TRIGGER IF EXISTS bio_audit_log_no_update ON bioestadistica.audit_log');
        $db->statement('DROP FUNCTION IF EXISTS bioestadistica.audit_log_reject_mutation()');
        Schema::connection('pgsql')->dropIfExists('bioestadistica.audit_log');
    }

    private function triggerExecuteKeyword($db): string
    {
        $version = (int) $db->selectOne("SELECT current_setting('server_version_num') AS v")->v;

        return $version >= 140000 ? 'EXECUTE FUNCTION' : 'EXECUTE PROCEDURE';
    }
};
