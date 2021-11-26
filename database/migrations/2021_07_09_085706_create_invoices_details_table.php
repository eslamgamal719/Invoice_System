<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices_details', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50);
            $table->string('product', 50);
            $table->string('section', 999);
            $table->string('status', 50);       //paid or unpaid
            $table->integer('value_status');    //paid = 1 , unpaid = 2 , paid_partially = 3
            $table->date('payment_date')->nullable();
            $table->text('note')->nullable();
            $table->string('user',300);        // created_by
            $table->unsignedBigInteger('id_invoice');
            $table->foreign('id_invoice')->references('id')->on('invoices')->onDelete('cascade');
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
        Schema::dropIfExists('invoices_details');
    }
}
