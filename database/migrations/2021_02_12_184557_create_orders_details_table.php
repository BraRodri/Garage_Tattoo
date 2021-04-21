<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orders_id')->constrained('orders');
            $table->string('code');
            $table->string('description');
            $table->string('combination');
            $table->integer('quantity');
            $table->integer('unit_price');
            $table->integer('total_price');
            $table->decimal('weight',7,3)->default(0.000);
            $table->decimal('lenght',7,3)->default(0.000);
            $table->decimal('width',7,3)->default(0.000);
            $table->decimal('height',7,3);
            $table->integer('shipping_free')->default(0);



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
        Schema::dropIfExists('orders_details');
    }
}
