<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();
            
            $table->uuid('uuid')->unique();

            $table->string('client_code')->default('000000');
            $table->string('invoice_code')->unique();
            $table->float('price_ht')->default(0);
            $table->float('price_total')->default(0);
            $table->string('status')->default('impayee');

            $table->string('remise')->default('0');

            $table->date('date_payment')->nullable();

            $table->date('date_due')->nullable();

            $table->foreignId('client_id')->index()->nullable()->constrained();
            $table->foreignId('ticket_id')->index()->nullable()->constrained();
            $table->foreignId('company_id')->index()->constrained();

            $table->boolean('active')->default(true);

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
        Schema::dropIfExists('invoices');
    }
}
