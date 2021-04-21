<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartListDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::disableForeignKeyConstraints();

        Schema::create('cart_list_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cart_list_id')->nullable()->constrained('cart_list')->onDelete('cascade');;
            $table->foreignId('products_id')->nullable()->constrained('products')->onDelete('cascade');;
            $table->integer('quantity');
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
        Schema::dropIfExists('cart_list_details');
    }
}
