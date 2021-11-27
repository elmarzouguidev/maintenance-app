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
            $table->string('nom');
            $table->string('prenom');
            $table->string('slug')->unique();
            $table->string('address')->nullable();
            $table->string('email')->unique();
            $table->string('gsm')->nullable();
            $table->string('telephone')->unique();

            $table->string('ste_name');
            $table->string('ste_ice')->unique()->nullable();
            $table->string('ste_rc')->unique()->nullable();
            $table->string('ste_logo')->nullable();

            $table->boolean('active')->default(true);
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
