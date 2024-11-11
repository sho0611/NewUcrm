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
        Schema::create('stripe_payments', function (Blueprint $table) {
            $table->id('stripe_payment_id');
            $table->foreignId('appointment_id')
            ->nullable()    
            ->references('appointment_id')
            ->on('appointments')     
            ->onUpdate('cascade')
            ->onDelete('cascade');

            $table->string('charge_id');
            $table->decimal('amount', 10, 2);
            $table->string('customer_id');
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
        Schema::dropIfExists('stripe_payments');
    }
};
