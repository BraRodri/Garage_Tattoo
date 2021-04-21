<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modals', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('description')->default('');
            $table->string('image')->nullable();
            $table->string('link')->nullable();
            $table->string('target')->nullable();
            $table->date('start_date');
            $table->date('end_date');
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
        Schema::dropIfExists('modals');
    }
}
