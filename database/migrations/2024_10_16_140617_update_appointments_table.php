<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Staff;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      
        $appointments = \App\Models\Appointment::all();

        foreach ($appointments as $appointment) {
           
            $appointment->service_id = Item::inRandomOrder()->first()->id;
            $appointment->customer_id = Customer::inRandomOrder()->first()->id;
            $appointment->staff_id = Staff::inRandomOrder()->first()->id;
            $appointment->appointment_date = now()->addDays(rand(0, 7))->format('Y-m-d');
            $appointment->appointment_time = now()->setTime(rand(9, 17), 0)->format('H:i'); 
            $appointment->save();
        }
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
