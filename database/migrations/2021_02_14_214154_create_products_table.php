<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();

            $table->foreignId('brands_id')->nullable()->constrained('brands');
            $table->foreignId('types_id')->constrained('types');
            $table->string('type', 50)->nullable();
            $table->string('sku', 100)->nullable();
            $table->string('title');
            $table->integer('normal_price')->default(0);
            $table->integer('offer_price')->default(0);
            $table->integer('stock_control')->default(0);
            $table->integer('stock')->default(0);
            $table->integer('minimum_amount')->default(0);
            $table->float('discounts')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('weight', 7, 3)->default(0.000);
            $table->decimal('lenght', 7, 3)->default(0.000);
            $table->decimal('width', 7, 3)->default(0.000);
            $table->decimal('height', 7, 3)->default(0.000);
            $table->longText('general_description')->default('');
            $table->longText('technical_description')->default('');
            $table->longText('shipping_description')->default('');
            $table->longText('guarantee_description')->default('');
            $table->longText('video_description')->default('');
            $table->string('model')->nullable();
            $table->longText('color')->default('');
            $table->longText('talla')->default('');
            $table->longText('medida')->default('');
            $table->integer('offer')->default(0);
            $table->integer('featured')->default(0);
            $table->integer('new')->default(0);
            $table->integer('a_pedido')->default(0);
            $table->integer('visit_number')->default(0);
            $table->integer('sales_number')->default(0);
            $table->integer('points')->default(0);
            $table->integer('position');
            $table->integer('shipping_active')->default(0);
            $table->integer('office_shipping_active')->default(0);
            $table->integer('shipping_free')->default(0);
            $table->integer('attribute_active')->default(0);
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->default('');
            $table->longText('meta_keyword')->default('');
            $table->string('meta_author')->nullable();
            $table->string('meta_robots')->nullable();
            $table->string('archive')->nullable();
            $table->string('certificado')->nullable();
            $table->string('chilecompracode')->nullable();
            $table->integer('active')->default(0);
            $table->string('code_cache')->nullable();
            $table->integer('pedregal_id')->nullable();
            $table->string('author')->nullable();



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
        Schema::dropIfExists('products');
    }
}
