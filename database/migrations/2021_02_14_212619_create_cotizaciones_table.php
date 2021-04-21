<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCotizacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('cotizaciones', function (Blueprint $table) {
            $table->id();

            $table->foreignId('offices_id')->nullable()->constrained('offices');
            $table->foreignId('clients_id')->nullable()->constrained('clients');
            $table->string('business_name');
            $table->string('rut',12);
            $table->string('commercial_business')->nullable();
            $table->string('email');
            $table->string('phone',50)->nullable();
            $table->string('address');
            $table->string('address_number',100);
            $table->string('office_number',100)->nullable();
            $table->string('region_name')->nullable();
            $table->string('province_name')->nullable();
            $table->string('location_name')->nullable();
            $table->integer('order_status');
            $table->longText('order_comment')->default('');
            $table->integer('subtotal');
            $table->integer('discount');
            $table->integer('shipping');
            $table->integer('extra');
            $table->integer('tax');
            $table->integer('total');




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
        Schema::dropIfExists('cotizaciones');
    }
}
