<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesDetalleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('cotizaciones_detalle', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cotizaciones_id')->constrained('cotizaciones');
            $table->string('code');
            $table->string('description');
            $table->string('combination');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('total_price');
            $table->decimal('weight',7,3)->default(0.000);
            $table->decimal('lenght',7,3)->default(0.000);
            $table->decimal('width',7,3)->default(0.000);
            $table->decimal('height',7,3)->default(0.000);
            $table->integer('shipping_free')->default(0);
            $table->string('talla')->nullable();
            $table->string('color')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cotizaciones_detalle');
    }
}
