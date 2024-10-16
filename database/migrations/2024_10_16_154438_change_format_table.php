<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('appointments')->get()->each(function ($appointment) {
            $formattedTime = date('H:i', strtotime($appointment->appointment_time));
            DB::table('appointments')
                ->where('id', $appointment->id)
                ->update(['appointment_time' => $formattedTime]);
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            //
        });
    }
};
