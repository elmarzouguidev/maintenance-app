<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimate_articles', function (Blueprint $table) {
 
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignId('estimate_id')->index()->constrained()->cascadeOnDelete();

            $table->longText('designation');
            $table->longText('description')->nullable();
            $table->bigInteger('quantity')->default(0);
            $table->float('prix_unitaire')->default(0);
            $table->float('montant_ht')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimate_articles');
    }
}
