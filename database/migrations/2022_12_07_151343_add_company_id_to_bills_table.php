<?php

declare(strict_types=1);

use App\Models\Finance\Company;
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
            $table->after('billable_type', function ($table) {
                $table->foreignIdFor(Company::class)->nullable()->constrained();
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('bills', function (Blueprint $table) {
            $table->dropColumn(['company_id']);
        });
    }
};
