<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('offices_id')->nullable()->constrained('offices');
            $table->foreignId('clients_id')->constrained('clients');
            $table->string('business_name');
            $table->string('rut');
            $table->string('commercial_business')->nullable();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('address');
            $table->string('address_number');
            $table->string('office_number')->nullable();
            $table->string('region_name')->nullable();
            $table->string('province_name')->nullable();
            $table->string('location_name')->nullable();
            $table->string('type_document')->nullable();
            $table->string('document_business_name')->nullable();
            $table->string('document_rut')->nullable();
            $table->string('document_commercial_business')->nullable();
            $table->string('document_phone')->nullable();
            $table->string('document_address')->nullable();
            $table->string('document_address_number')->nullable();
            $table->string('document_office_number')->nullable();
            $table->string('document_region_number')->nullable();
            $table->string('document_province_number')->nullable();
            $table->string('document_region_name')->nullable();
            $table->string('document_province_name')->nullable();
            $table->string('document_location_name')->nullable();
            $table->integer('shipping_type');
            $table->integer('payment_type');
            $table->integer('shipping_status');
            $table->integer('payment_status');
            $table->longText('order_comment')->default('');
            $table->longText('shipping_comment')->default('');
            $table->string('discount_code')->nullable();
            $table->integer('subtotal');
            $table->integer('discount')->default(0);
            $table->integer('shipping')->default(0);
            $table->integer('extra')->default(0);
            $table->integer('tax')->default(0);
            $table->integer('total');
            $table->integer('width')->default(0);
            $table->integer('height')->default(0);
            $table->integer('length')->default(0);
            $table->integer('weight')->default(0);
            $table->string('shipit_courier_name')->nullable();
            $table->integer('shipit_commune_id')->nullable();
            $table->string('shipit_id');


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
        Schema::dropIfExists('orders');
    }
}
