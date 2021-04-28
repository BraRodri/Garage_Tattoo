<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCombinacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combinaciones', function (Blueprint $table) {
            $table->id();
            $table->string('sku');
            $table->string('stock');
            $table->string('price');
            $table->string('products_id')->constrained('products');;
            $table->string('attibutes_products')->constrained('attibutes_products');
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
        Schema::dropIfExists('combinaciones');
    }
}
