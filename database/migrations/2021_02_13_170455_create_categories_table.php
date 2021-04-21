<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('slug')->unique();
            $table->integer('parent_id')->default(0);
            $table->integer('level');
            $table->string('title');
            $table->longText('description')->default('');
            $table->string('main_image')->nullable();
            $table->string('secondary_image')->nullable();
            $table->string('offer_image')->nullable();
            $table->float('discount')->default(0);
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('position');
            $table->tinyInteger('featured')->default(0);
            $table->tinyInteger('active')->default(0);
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
        Schema::dropIfExists('categories');
    }
}
