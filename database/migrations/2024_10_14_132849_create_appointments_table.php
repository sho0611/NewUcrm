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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')
            ->constrained('items')
            ->onUpdate('cascade') 
            ->onDelete('cascade');

            $table->foreignId('customer_id')
            ->constrained()
            ->onUpdate('cascade') 
            ->onDelete('cascade');

            $table->foreignId('staff_id')
            ->constrained()
            ->onUpdate('cascade') 
            ->onDelete('cascade');

            $table->date('appointment_date');
            $table->time('appointment_time'); 
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
        Schema::dropIfExists('appointments');
    }
};
