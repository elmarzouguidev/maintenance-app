<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAuthIdToTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {

            $table->after('client_id', function ($table) {

                $table->foreignId('admin_id')
                    ->nullable()
                    ->index()
                    ->constrained();
                $table->foreignId('reception_id')
                    ->nullable()
                    ->index()
                    ->constrained();

                $table->foreignId('technicien_id')
                    ->nullable()
                    ->index()
                    ->constrained();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn(['admin_id', 'reception_id', 'technicien_id']);
            $table->dropForeign(['tickets_admin_id_index', 'tickets_reception_id_index', 'tickets_technicien_id_index']);
        });
    }
}
