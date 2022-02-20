<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('status_id')
                ->index()
                ->constrained();

            $table->foreignId('ticket_id')
                ->index()
                ->constrained();
            $table->foreignId('user_id')->nullable();
            $table->longText('description')->nullable();
            $table->dateTime('changed_at');

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
        Schema::dropIfExists('ticket_status');
    }
}
