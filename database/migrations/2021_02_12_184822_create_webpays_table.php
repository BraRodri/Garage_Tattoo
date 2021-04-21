<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebpaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webpays', function (Blueprint $table) {
            $table->id();

            $table->string('tbk_transaccion')->default('');
            $table->string('tbk_orden_compra')->default('');
            $table->string('tbk_id_session')->default('');;
            $table->string('tbk_fecha_contable')->default('');;
            $table->string('tbk_fecha_transaccion')->default('');;
            $table->string('tbk_hora_transaccion')->default('');;
            $table->string('tbk_numero_final_tarjeta')->default('');;
            $table->string('tbk_fecha_expiracion_tarjeta')->default('');;
            $table->string('tbk_codigo_autorizacion')->default('');;
            $table->string('tbk_codigo_tipo_pago')->default('');;
            $table->string('tbk_codigo_respuesta')->default('');;
            $table->string('tbk_descripcion_respuesta')->default('');;
            $table->decimal('tbk_monto',13,4)->default(0.0000);
            $table->decimal('tbk_valor_cuotas',13,4)->default(0.0000);
            $table->integer('tbk_numero_cuotas')->default(0);
            $table->string('tbk_codigo_comercio')->default('');;
            $table->string('tbk_orden_compra_comercio')->default('');;
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
        Schema::dropIfExists('webpays');
    }
}
