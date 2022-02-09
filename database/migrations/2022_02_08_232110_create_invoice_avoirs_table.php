<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceAvoirsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_avoirs', function (Blueprint $table) {
            $table->id();

            $table->uuid('uuid')->unique();
            $table->string('full_number')->unique();

            $table->string('client_code')->default('000000');
            $table->string('invoice_code');
            
            $table->float('price_ht')->default(0);
            $table->float('price_total')->default(0);
            $table->float('price_tva')->default(0);

            $table->string('status')->default('impayee');

            $table->string('remise')->default('0');

            $table->date('date_invoice');
            $table->date('date_due')->nullable();
            $table->date('date_payment')->nullable();

            $table->foreignId('client_id')->index()->nullable()->constrained();
            $table->foreignId('ticket_id')->index()->nullable()->constrained();
            $table->foreignId('company_id')->index()->constrained();
            $table->foreignId('invoice_id')->index()->nullable();

            $table->mediumText('admin_notes')->nullable();
            $table->mediumText('client_notes')->nullable();
            $table->mediumText('condition_general')->nullable();

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
        Schema::dropIfExists('invoice_avoirs');
    }
}
