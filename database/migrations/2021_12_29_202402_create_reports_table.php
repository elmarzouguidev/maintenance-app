<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            //$table->string('ticket')->unique();
            $table->longText('content');
            $table->foreignId('ticket_id')->nullable()->references('id')->on('tickets')->onDelete('cascade');
            $table->foreignId('technicien_id')->nullable()->references('id')->on('techniciens');
            $table->boolean('active')->default(true);
            $table->enum('type', ['diagnostique', 'reparation'])->default('diagnostique');
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
        Schema::dropIfExists('reports');
    }
}
