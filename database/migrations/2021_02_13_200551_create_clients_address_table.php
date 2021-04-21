<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients_address', function (Blueprint $table) {
            $table->id();

            $table->foreignId('clients_id')->constrained('clients');
            $table->foreignId('regions_id')->constrained('regions');
            $table->foreignId('provinces_id')->constrained('provinces');
            $table->foreignId('locations_id')->constrained('locations');
            $table->integer('address_default')->default(0);
            $table->string('address');
            $table->string('address_number');
            $table->string('office_number')->nullable();
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
        Schema::dropIfExists('clients_address');
    }
}
