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
        Schema::table('invoices', function (Blueprint $table) {
            $table->boolean('has_header')->default(true)->after('company_id');
        });

        Schema::table('invoice_avoirs', function (Blueprint $table) {
            $table->boolean('has_header')->default(true)->after('company_id');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->boolean('has_header')->default(true)->after('company_id');
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->boolean('has_header')->default(true)->after('company_id');
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('has_header');
        });

        Schema::table('invoice_avoirs', function (Blueprint $table) {
            $table->dropColumn('has_header');
        });

        Schema::table('estimates', function (Blueprint $table) {
            $table->dropColumn('has_header');
        });

        Schema::table('b_commands', function (Blueprint $table) {
            $table->dropColumn('has_header');
        });
    }
};
