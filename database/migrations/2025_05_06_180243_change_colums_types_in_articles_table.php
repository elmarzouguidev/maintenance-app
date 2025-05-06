<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @return void
     */
    public function up(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->float('quantity')->default(0)->change();
            $table->float('prix_unitaire')->default(0)->change();
            $table->float('montant_ht')->default(0)->change();
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->float('price_ht')->nullable()->change();
            $table->float('price_total')->nullable()->change();
            $table->float('price_tva')->nullable()->change();
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('articles', function (Blueprint $table) {
            $table->bigInteger('quantity')->default(0)->change();
            $table->unsignedBigInteger('prix_unitaire')->default(0)->change();
            $table->unsignedBigInteger('montant_ht')->default(0)->change();
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->unsignedBigInteger('price_ht')->nullable()->change();
            $table->unsignedBigInteger('price_total')->nullable()->change();
            $table->unsignedBigInteger('price_tva')->nullable()->change();
        });
    }
};
