<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use ZipStream\ZipStream;

class CreateSlidersClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sliders_clients', function (Blueprint $table) {
            $table->id();

            $table->string('location',50)->nullable();
            $table->string('title');
            $table->longText('description')->default('');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('target')->nullable();
            $table->integer('position');
            $table->integer('active')->default(0);
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
        Schema::dropIfExists('sliders_clients');
    }
}
