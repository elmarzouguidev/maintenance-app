<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique()->nullable();
            $table->string('unique_code')->unique();

            $table->string('article');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->string('photo')->nullable();
            $table->longText('photos')->nullable();
        
            $table->boolean('active')->default(false);
            $table->boolean('published')->default(false);

            $table->enum('etat', ['non-traite', 'reparable', 'non-reparable'])
                ->default('non-traite');

            $table->string('status')
                ->default('new');

            $table->foreignId('client_id')
                ->nullable()
                ->index()
                ->constrained();
                

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
        Schema::dropIfExists('tickets');
    }
}
