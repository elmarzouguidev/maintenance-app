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
        Schema::table('bills', function (Blueprint $table) {
            $table->after('company_id', function ($table) {
                $table->string('added_by')->nullable();
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn('added_by');
        });
    }
};
