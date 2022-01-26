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
            
            $table->string('estimate_code')->unique();
            $table->float('price_ht')->default(0);
            $table->float('price_total')->default(0);
            $table->float('total_tva')->default(0);

            $table->string('status')->default('accepte');

            $table->date('estimate_date')->nullable();

            $table->date('date_due')->nullable();

            $table->foreignId('invoice_id')->index()->nullable()->constrained();
            $table->foreignId('client_id')->index()->nullable()->constrained();
            $table->foreignId('ticket_id')->index()->nullable()->constrained();
            $table->foreignId('company_id')->index()->constrained();

            $table->boolean('active')->default(true);

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
