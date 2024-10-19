<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;
use App\Models\Customer;
use App\Models\Staff;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Appointment>
 */
class AppointmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start = \Carbon\Carbon::createFromTime(9, 0); 
        $end = \Carbon\Carbon::createFromTime(18, 0); 
        $randomTime = $this->faker->dateTimeBetween($start, $end);
    
        return [
            'service_id' => Item::inRandomOrder()->first()->id, 
            'customer_id' => Customer::inRandomOrder()->first()->id, 
            'staff_id' => Staff::inRandomOrder()->first()->id, 
            'appointment_date' => now()->addDays(rand(0, 7))->format('Y-m-d'),
            'appointment_time' => $randomTime->format('H:i'),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
