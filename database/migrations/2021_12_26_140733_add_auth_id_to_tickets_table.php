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
            $table->unsignedBigInteger('admin_id')->nullable()->after('published');
            $table->foreign('admin_id')->references('id')->on('admins');

            $table->unsignedBigInteger('reception_id')->nullable()->after('admin_id');
            $table->foreign('reception_id')->references('id')->on('receptions');

            $table->unsignedBigInteger('technicien_id')->nullable()->after('reception_id');
            $table->foreign('technicien_id')->references('id')->on('techniciens');
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
            $table->dropForeign(['tickets_admin_id_foreign', 'tickets_reception_id_foreign', 'tickets_technicien_id_foreign']);
        });
    }
}
