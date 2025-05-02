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
            $table->after('full_number', function ($table) {
                $table->string('bc_number')->nullable();
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('blivraisons', function (Blueprint $table) {
           $table->dropColumn('bc_number');
        });
    }
};
