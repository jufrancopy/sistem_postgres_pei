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
            $table->string('type'); //BIEN INMUEBLE DE:
            $table->integer('quantityAccount'); //CANTIDAD DE CTAS CTES
            $table->string('detailLocation'); //LOTE/MA/DPTO.
            $table->integer('estateQuantity'); //CANTIDAD  DE FINCAS
            $table->string('department'); //DEPARTAMENTO
            $table->string('city'); //Ciudad
            $table->string('locality'); //DISTRITO - CIUDAD
            $table->string('description'); //DESCRIPCION 
            $table->decimal('latitude'); //LATITUD 
            $table->decimal('longitude'); //LONGITUD 
            $table->string('location'); //UBICACIÓN
            $table->string('infrastructureType'); //Tipo de Infraestructura
            $table->date('startDateContract'); //Fecha de Inicio del Contrato
            $table->date('endDateContract'); //Fecha Fin del Contrato
            $table->string('registryNumber');  //FINCA MATRICULA Nº             
            $table->integer('cadastreCurrentAccount');  //CTA. CTE. CTRAL.  Y/0 PADRON N° 
            $table->integer('estateStatus'); //SITUACION ACTUAL DEL INMUEBLE 
            $table->integer('committedInvestment'); //INVERSION COMPROMETIDA 
            $table->integer('transfer'); //TRANFERENCIA   
            $table->integer('balanceForTransfer'); //SALDO A TRANSFERIR 
            $table->string('tenant'); //ARRENDATARIO
            $table->string('rentAmount'); //CANON
            $table->integer('rentAmountPeriod'); //PERIODO DEL CANON
            $table->string('contractResolution'); //PERIODO VIGENTE-DESDE 
            $table->string('currentPeriodEnd'); //PERIODO VIGENTE-HASTA 
            $table->string('statusDocumentation'); //TENENCIA DE TITULO
            $table->float('landAreaMt2'); //SUPERFICIE TERRENO M2
            $table->float('landAreaHectares'); //SUPERFICIE TERRENO HA 
            $table->string('landSubArea'); //SUPERFICIE (SOLO PARA LOS SUB NUMEROS)
            $table->string('builtAreaM2'); //SUPERFICIE EDIFICADA M2
            $table->string('builtValueGs'); //Valor Edificado Gs.
            $table->string('propertyValueGs'); //Valor Terreno Gs.
            $table->string('totalValueGs'); //Valor Total.
            $table->string('possessionRentWithoutTitle'); //Valor Total.

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
