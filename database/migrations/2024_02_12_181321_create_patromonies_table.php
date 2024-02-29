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
            $table->text('type'); // BIEN INMUEBLE DE:
            $table->string('quantity_account_current'); // CANTIDAD DE CTAS CTES
            $table->string('detail_location'); // LOTE/MA/DPTO.
            $table->string('estate_quantity'); // CANTIDAD  DE FINCAS
            $table->string('department'); // DEPARTAMENTO
            $table->string('city'); // Ciudad
            $table->string('locality'); // DISTRITO - CIUDAD
            $table->decimal('latitude'); // LATITUD 
            $table->decimal('longitude'); // LONGITUD 
            $table->string('location_address'); // UBICACIÓN
            $table->string('infrastructure_type'); // Tipo de Infraestructura
            $table->text('description'); // UBICACIÓN
            $table->string('registry_number');  // FINCA MATRICULA Nº             
            $table->string('cadastral_current_account');  // CTA. CTE. CTRAL.  Y/0 PADRON N° 
            $table->string('estate_status'); // SITUACION ACTUAL DEL INMUEBLE
            $table->string('start_date_contract'); // Fecha de Inicio del Contrato
            $table->string('end_date_contract'); // Fecha Fin del Contrato 
            $table->string('committed_investment'); // INVERSION COMPROMETIDA 
            $table->string('transfer'); // TRANFERENCIA   
            $table->string('balance_for_transfer'); // SALDO A TRANSFERIR 
            $table->string('tenant'); // ARRENDATARIO
            $table->string('rent_amount'); // CANON
            $table->integer('rent_amount_period'); // PERIODO DEL CANON
            $table->string('contract_resolution'); // PERIODO VIGENTE-DESDE 
            $table->string('contract_number'); // NRO DE CONTRATO
            $table->string('current_period_start'); // PERIODO VIGENTE-DESDE 
            $table->string('current_period_end'); // PERIODO VIGENTE-HASTA 
            $table->string('status_documentation'); // TENENCIA DE TITULO
            $table->string('land_area_mt2'); // SUPERFICIE TERRENO M2
            $table->string('land_area_hectares'); // SUPERFICIE TERRENO HA 
            $table->string('land_sub_area'); // SUPERFICIE (SOLO PARA LOS SUB NUMEROS)
            $table->string('built_area_m2'); // SUPERFICIE EDIFICADA M2
            $table->string('built_value_gs'); // Valor Edificado Gs.
            $table->string('property_value_gs'); // Valor Terreno Gs.
            $table->string('total_value_gs'); // Valor Total.
            $table->string('possession_rent_without_title'); // Valor Total.

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
