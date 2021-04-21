<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();

            $table->string('address');
            $table->string('city');
            $table->string('address_2');
            $table->string('city_2');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('phone4')->nullable();
            $table->string('fax')->nullable();
            $table->string('email');
            $table->string('email2')->nullable();
            $table->string('email3')->nullable();
            $table->string('contact_email');
            $table->string('sale_email');
            $table->string('suscription_email');
            $table->string('cotizacion_email');
            $table->longText('map_1')->default('');
            $table->longText('map_2')->default('');
            $table->longText('map_1_link')->default('');
            $table->longText('map_2_link')->default('');
            $table->text('horary')->default('');
            $table->longText('tranfer_text')->default('');
            $table->longText('webpay_text')->default('');
            $table->integer('minimum_sale')->default(0);
            $table->integer('minimum_free_shipping')->default(0);
            $table->longText('shipping_text_for_paying')->default('');
            $table->longText('shipping_text')->default('');
            $table->longText('office_shipping_text')->default('');
            $table->integer('discount_minimum')->default(0);
            $table->float('discount_percentage')->default(0);
            $table->integer('shipping_active')->default(0);
            $table->integer('office_shipping_active')->default(0);
            $table->integer('transfer_active')->default(0);
            $table->integer('webpay_active')->default(0);
            $table->string('social_facebook')->nullable();
            $table->string('social_instagram')->nullable();
            $table->string('social_linkedin')->nullable();
            $table->string('social_twitter')->nullable();
            $table->string('social_youtube')->nullable();
            $table->integer('site_offline')->default(0);
            $table->string('shipping_type');
            $table->tinyInteger('shipit_environment')->default(0);
            $table->string('shipit_email')->nullable();
            $table->string('shipit_token')->nullable();
            $table->integer('shipit_tax')->default(0);
            $table->string('webpay_name_company');
            $table->string('webpay_code')->nullable();
            $table->tinyInteger('webpay_environment')->default(0);
            $table->longText('webpay_private_key')->default('');
            $table->longText('webpay_public_cert')->default('');
            $table->longText('webpay_tbk_cert')->default('');
            $table->integer('webpay_tax')->default(0);
            $table->tinyInteger('active_tax')->default(0);
            $table->tinyInteger('active_cart')->default(1);
            $table->tinyInteger('active_cotizacion')->default(1);
            $table->string('author');

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
        Schema::dropIfExists('configurations');
    }
}
