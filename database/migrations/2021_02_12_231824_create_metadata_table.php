<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();

            $table->integer('friendly_url')->default(0);
            $table->string('title');
            $table->string('authors')->nullable();
            $table->string('subject')->nullable();
            $table->text('description')->default('');
            $table->longText('keyword')->default('');
            $table->string('language')->nullable();
            $table->string('indexing')->nullable();
            $table->string('robots')->nullable();
            $table->string('googlebots')->nullable();
            $table->string('distribution')->nullable();
            $table->string('googlecode')->nullable();
            $table->string('analyticcode')->nullable();
            $table->string('pixelcode')->nullable();
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
        Schema::dropIfExists('metadata');
    }
}
