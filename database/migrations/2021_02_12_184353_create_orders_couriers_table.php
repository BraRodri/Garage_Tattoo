<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersCouriersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders_couriers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orders_id')->constrained('orders');
            $table->foreignId('couriers_id')->constrained('couriers');
            $table->string('number');
            $table->string('link');
            $table->string('message');
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
        Schema::dropIfExists('orders_couriers');
    }
}
