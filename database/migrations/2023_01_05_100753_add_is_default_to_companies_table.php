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
            $table->after('uuid',function($table){
               $table->boolean('is_default')->default(false);
            });
        });
    }

    /**
     * @return void
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('is_default');
        });
    }
};
