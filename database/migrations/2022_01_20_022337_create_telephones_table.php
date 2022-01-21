<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTelephonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('telephones', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique()->nullable();

            $table->foreignId('client_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('telephone')->unique();
            $table->string('type')->default('portable');

            $table->boolean('primary')->default(false);
            $table->boolean('active')->default(true);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('telephones');
    }
}
