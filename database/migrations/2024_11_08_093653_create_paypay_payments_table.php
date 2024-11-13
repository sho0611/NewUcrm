<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paypay_payments', function (Blueprint $table) {
            $table->id('paypay_payments_id');
            $table->integer('price')->comment('料金');
            $table->boolean('is_payment')->default(false)->comment('決済判定');
            $table->string('paypay_merchant_payment_id')->nullable()->comment('PayPay 決済ID');
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
        Schema::dropIfExists('orders');
    }
};
