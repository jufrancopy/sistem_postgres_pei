<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_configurations', function (Blueprint $table) {
            $table->string('site_name')->nullable()->default('Sistema PEI & Gestión Estratégica — IPS');
            $table->text('logo_url')->nullable();
            $table->string('contact_email')->nullable()->default('planificacion@ips.gov.py');
            $table->string('contact_phone')->nullable()->default('+595 21 219 7000');
            $table->string('opening_hours')->nullable()->default('Lunes a Viernes de 07:00 a 15:00 hs');
            $table->text('address')->nullable()->default('Constitución e/ Herrera y Pettirossi, Asunción - Paraguay');
            $table->text('footer_text')->nullable()->default('© 2026 Instituto de Previsión Social (IPS) — Dirección de Planificación. Todos los derechos reservados.');
            $table->json('system_parameters')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('home_configurations', function (Blueprint $table) {
            $table->dropColumn([
                'site_name',
                'logo_url',
                'contact_email',
                'contact_phone',
                'opening_hours',
                'address',
                'footer_text',
                'system_parameters',
            ]);
        });
    }
};
