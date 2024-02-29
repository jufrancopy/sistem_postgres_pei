<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patrimonies', function (Blueprint $table) {
            $table->id();
            $table->text('type')->nullable(); // BIEN INMUEBLE DE:
            $table->string('quantity_account_current'); // CANTIDAD DE CTAS CTES
            $table->string('detail_location')->nullable(); // LOTE/MA/DPTO.
            $table->string('estate_quantity')->nullable(); // CANTIDAD  DE FINCAS
            $table->string('department')->nullable(); // DEPARTAMENTO
            $table->string('city')->nullable(); // Ciudad
            $table->string('locality')->nullable(); // DISTRITO - CIUDAD
            $table->float('latitude')->nullable(); // LATITUD 
            $table->float('longitude')->nullable(); // LONGITUD 
            $table->string('location_address')->nullable(); // UBICACIÓN
            $table->string('infrastructure_type'); // Tipo de Infraestructura
            $table->text('description')->nullable(); // UBICACIÓN
            $table->string('registry_number');  // FINCA MATRICULA Nº             
            $table->string('cadastral_current_account')->nullable();  // CTA. CTE. CTRAL.  Y/0 PADRON N° 
            $table->string('estate_status')->nullable(); // SITUACION ACTUAL DEL INMUEBLE
            $table->string('committed_investment')->nullable(); // INVERSION COMPROMETIDA 
            $table->string('transfer')->nullable(); // TRANFERENCIA   
            $table->string('balance_for_transfer')->nullable(); // SALDO A TRANSFERIR 
            $table->text('tenant')->nullable(); // ARRENDATARIO
            $table->string('rent_amount')->nullable(); // CANON
            $table->string('rent_amount_period')->nullable(); // PERIODO DEL CANON
            $table->string('contract_resolution')->nullable(); // PERIODO VIGENTE-DESDE 
            $table->string('contract_number')->nullable(); // NRO DE CONTRATO
            $table->string('current_period_start')->nullable(); // PERIODO VIGENTE-DESDE 
            $table->string('current_period_end')->nullable(); // PERIODO VIGENTE-HASTA 
            $table->string('status_documentation')->nullable(); // TENENCIA DE TITULO
            $table->string('land_area_mt2')->nullable(); // SUPERFICIE TERRENO M2
            $table->string('land_area_hectares')->nullable(); // SUPERFICIE TERRENO HA 
            $table->string('land_sub_area')->nullable(); // SUPERFICIE (SOLO PARA LOS SUB NUMEROS)
            $table->string('built_area_m2')->nullable(); // SUPERFICIE EDIFICADA M2
            $table->string('built_value_gs'); // Valor Edificado Gs.
            $table->string('property_value_gs')->nullable(); // Valor Terreno Gs.
            $table->string('total_value_gs')->nullable(); // Valor Total.
            $table->string('possession_rent_without_title')->nullable(); // Valor Total.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patrimonies');
    }
};
