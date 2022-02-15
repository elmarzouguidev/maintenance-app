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
            $table->string('code')->unique();

            $table->string('article');
            $table->string('article_reference')->nullable();

            $table->longText('description');
            
            $table->string('photos')->nullable();
        
            $table->boolean('active')->default(false);
            $table->boolean('published')->default(false);

            $table->string('etat')->default('non-diagnostiquer');
            $table->string('status')->default('non-traite');

            $table->integer('priority')->default(1);
            $table->boolean('can_invoiced')->default(false);

            $table->foreignId('client_id')
                ->nullable()
                ->index()
                //->constrained();
                ->cascadeOnDelete();

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
