<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExternalIdToAllTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private $tables = ['users', 'admins', 'techniciens', 'receptions', 'tickets', 'categories', 'clients', 'products'];

    public function up()
    {

        foreach ($this->tables as $tbl) {
            
            if (!Schema::hasColumn($tbl, 'external_id')) {

                Schema::table($tbl, function (Blueprint $table) {
                    $table->uuid('external_id')->unique()->nullable()->after('id');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $tbl) {
            Schema::table($tbl, function (Blueprint $table) {

                $table->dropColumn('external_id');
            });
        }
    }
}
