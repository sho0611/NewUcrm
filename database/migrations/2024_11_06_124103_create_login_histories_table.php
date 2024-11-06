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
        Schema::create('login_histories', function (Blueprint $table) {
            $table->id('login_histories_id');
            $table->foreignId('staff_id')
            ->onUpdate('cascade')
            ->onDelete('cascade');  

            $table->foreignId('account_id')
            ->onUpdate('cascade')   
            ->onDelete('set null'); 

            $table->timestamp('login_time')->nullable();
            $table->timestamp('logout_time')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('login_histories');
    }
};
