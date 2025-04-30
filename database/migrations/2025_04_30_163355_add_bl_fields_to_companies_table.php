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
        Schema::table('companies', function (Blueprint $table) {
            $table->after('bcommand_start_number', function ($table) {
                $table->string('prefix_blivraison')->default('BL-');
                $table->bigInteger('blivraison_start_number')->default(1);
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['prefix_bl', 'bl_start_number']);
        });
    }
};
