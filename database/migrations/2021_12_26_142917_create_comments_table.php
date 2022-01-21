<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique()->nullable();

            $table->longText('content');

            $table->foreignId('ticket_id')
                ->nullable()
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('admin_id')
                ->nullable()
                ->index()
                ->constrained();
                
            $table->foreignId('reception_id')
                ->nullable()
                ->index()
                ->constrained()
                ->deleteOnCascade();

            $table->foreignId('technicien_id')
                ->nullable()
                ->index()
                ->constrained()
                ->deleteOnCascade();

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
        Schema::dropIfExists('comments');
    }
}
