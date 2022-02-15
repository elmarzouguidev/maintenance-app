<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEstimatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estimates', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('code')->unique();
            $table->string('full_number')->unique()->nullable();

            $table->float('price_ht')->default(0);
            $table->float('price_total')->default(0);
            $table->float('price_tva')->default(0);

            $table->string('status')->default('accepte');

            $table->date('estimate_date')->nullable();
            $table->date('due_date')->nullable();

            $table->foreignId('invoice_id')->index()->nullable();
            $table->foreignId('client_id')->index()->nullable();
            $table->foreignId('ticket_id')->index()->nullable();
            $table->foreignId('company_id')->index()->constrained();

            $table->boolean('active')->default(true);
            $table->boolean('is_invoiced')->default(false);
            
            $table->timestamps();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estimates');
    }
}
