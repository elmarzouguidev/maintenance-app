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
        Schema::table('b_livraisons', function (Blueprint $table) {
            $table->after('date_due', function ($table) {
                $table->string('city')->nullable()->default('Casablanca');
                $table->text('full_address')->nullable();
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('b_livraisons', function (Blueprint $table) {
            $table->dropColumn(['full_address','city']);
        });
    }
};
