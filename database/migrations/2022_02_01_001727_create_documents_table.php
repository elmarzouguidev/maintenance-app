<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('title');
            $table->string('description')->nullable();
            $table->date('documented_at')->nullable();

            $table->foreignId('invoice_id')->constrained()->nullable();
            $table->foreignId('client_id')->constrained()->nullable();
            $table->foreignId('ticket_id')->constrained()->nullable();

            $table->enum('type', ['bl', 'bc', 'document'])->default('document'); // bc =bon de command ; bl =bon de livraison
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
        Schema::dropIfExists('documents');
    }
}
