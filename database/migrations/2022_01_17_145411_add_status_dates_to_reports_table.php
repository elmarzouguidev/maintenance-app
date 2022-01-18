<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusDatesToReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->timestamp('ouvert_at')->nullable();
            $table->timestamp('envoyer_at')->nullable();
            $table->timestamp('annuler_at')->nullable();
            $table->timestamp('attentdevis_at')->nullable();
            $table->timestamp('confirme_at')->nullable();
            $table->timestamp('encours_at')->nullable();
            $table->timestamp('finalizer_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reports', function (Blueprint $table) {
            $table->dropColumn(['ouvert_at', 'envoyer_at', 'annuler_at', 'attentdevis_at', 'confirme_at', 'finalizer_at', 'encours_at']);
        });
    }
}
