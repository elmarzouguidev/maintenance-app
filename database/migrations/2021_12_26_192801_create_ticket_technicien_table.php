<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketTechnicienTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_technicien', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique()->nullable();

            $table->foreignId('technicien_id')
            ->index()
            ->constrained();

            $table->foreignId('ticket_id')
            ->index()
            ->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_technicien');
    }
}
