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
            $table->uuid('uuid')->unique()->nullable();

            $table->string('entreprise');
            $table->string('slug')->unique();
            $table->string('contact');
            $table->string('telephone')->unique();
            $table->string('email')->unique()->nullable();
            $table->longText('addresse');

            $table->string('rc')->unique()->nullable();
            $table->string('ice')->unique();
            $table->string('logo')->nullable();
            $table->longText('description')->nullable();

            $table->boolean('active')->default(true);

            $table->foreignId('category_id')
                ->nullable()
                ->index()
                ->constrained();

            $table->timestamps();
            $table->dateTime('published_at')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
