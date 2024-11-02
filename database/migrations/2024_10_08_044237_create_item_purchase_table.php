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
        Schema::create('item_purchase', function (Blueprint $table) {
            
            $table->id('item_purchase_id');

            $table->foreignId('item_id')
            ->references('item_id')
            ->on('items')
            ->onDelete('cascade')
            ->onDelete('cascade');

            $table->foreignId('purchase_id')
            ->references('purchase_id')
            ->on('purchases')
            ->onDelete('cascade')
            ->onDelete('cascade');
                
            $table->integer('quantity');
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
        Schema::dropIfExists('item_purchase');
    }
};
