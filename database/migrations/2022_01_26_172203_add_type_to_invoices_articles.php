<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToInvoicesArticles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices_articles', function (Blueprint $table) {
            $table->enum('type', ['invoice', 'estimate'])->default('invoice')->after('montant_ht');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices_articles', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
