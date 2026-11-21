<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Store monetary values as fixed-point decimals instead of floats.
     *
     * MySQL rounds existing values to two decimals while altering the columns,
     * so take a backup and rehearse this migration on a copy of the production
     * database before running it there.
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->decimal('prix_unitaire', 12, 2)->default(0)->change();
            $table->decimal('montant_ht', 12, 2)->default(0)->change();
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->nullable()->change();
            $table->decimal('price_total', 12, 2)->nullable()->change();
            $table->decimal('price_tva', 12, 2)->nullable()->change();
        });

        Schema::table('b_livraisons', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->nullable()->change();
            $table->decimal('price_total', 12, 2)->nullable()->change();
            $table->decimal('price_tva', 12, 2)->nullable()->change();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->default(0)->change();
            $table->decimal('price_total', 12, 2)->default(0)->change();
            $table->decimal('price_tva', 12, 2)->default(0)->change();
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_total', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_tva', 12, 2)->nullable()->default(0)->change();
            $table->decimal('ht_price_remise', 12, 2)->nullable()->default(0)->change();
        });

        Schema::table('invoice_avoirs', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_total', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_tva', 12, 2)->nullable()->default(0)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->decimal('price_ht', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_total', 12, 2)->nullable()->default(0)->change();
            $table->decimal('price_tva', 12, 2)->nullable()->default(0)->change();
            $table->decimal('ht_price_remise', 12, 2)->nullable()->default(0)->change();
        });
    }

    /**
     * Revert monetary values to double precision floats.
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->float('prix_unitaire')->default(0)->change();
            $table->float('montant_ht')->default(0)->change();
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->change();
            $table->float('price_total')->nullable()->change();
            $table->float('price_tva')->nullable()->change();
        });

        Schema::table('b_livraisons', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->change();
            $table->float('price_total')->nullable()->change();
            $table->float('price_tva')->nullable()->change();
        });

        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('price_ht')->default(0)->change();
            $table->unsignedBigInteger('price_total')->default(0)->change();
            $table->unsignedBigInteger('price_tva')->default(0)->change();
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->default(0)->change();
            $table->float('price_total')->nullable()->default(0)->change();
            $table->float('price_tva')->nullable()->default(0)->change();
            $table->float('ht_price_remise')->nullable()->default(0)->change();
        });

        Schema::table('invoice_avoirs', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->default(0)->change();
            $table->float('price_total')->nullable()->default(0)->change();
            $table->float('price_tva')->nullable()->default(0)->change();
        });

        Schema::table('invoices', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->default(0)->change();
            $table->float('price_total')->nullable()->default(0)->change();
            $table->float('price_tva')->nullable()->default(0)->change();
            $table->float('ht_price_remise')->nullable()->default(0)->change();
        });
    }
};
