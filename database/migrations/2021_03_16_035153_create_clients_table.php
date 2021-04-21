<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();

  
            $table->string('type',50)->nullable();
            $table->string('rut',12);
            $table->string('business_name');
            $table->string('commercial_business')->nullable();
            $table->string('address')->nullable();
            $table->string('address_number')->nullable();
            $table->string('office_number')->nullable();
            $table->foreignId('regions_id')->nullable()->constrained('regions');
            $table->foreignId('provinces_id')->nullable()->constrained('provinces');
            $table->foreignId('locations_id')->nullable()->constrained('locations');
            $table->string('phone',50)->nullable();
            $table->string('email',100)->nullable()->unique();
            $table->string('password');
            $table->string('image')->nullable();
            $table->integer('active')->default(0);
            $table->string('author')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('clients');
    }
}
