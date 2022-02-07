<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {

            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('full_number')->unique();
            $table->string('ref')->unique()->nullable();

            $table->float('price_ht')->default(0);
            $table->float('price_total')->default(0);
            $table->float('price_tva')->default(0);

            $table->string('status')->default('accepted');
            $table->string('bill_mode')->default('virement-bancaire');

            $table->date('bill_date')->nullable();

            $table->foreignId('invoice_id')->index()->constrained();
            $table->foreignId('company_id')->index()->constrained();
            $table->foreignId('client_id')->index()->constrained();
            $table->foreignId('ticket_id')->index()->nullable();

            $table->mediumText('notes')->nullable();
            
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
        Schema::dropIfExists('bills');
    }
}
