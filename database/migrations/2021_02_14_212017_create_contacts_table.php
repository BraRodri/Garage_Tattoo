<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();

            $table->string('type',100);
            $table->string('name')->nullable();
            $table->string('email',100);
            $table->string('phone',50)->nullable();
            $table->string('mobile',50)->nullable();
            $table->string('city',50)->nullable();
            $table->longText('message')->default('');
            $table->foreignId('offices_id')->nullable()->constrained('offices');
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
        Schema::dropIfExists('contacts');
    }
}
