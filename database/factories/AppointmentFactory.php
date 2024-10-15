<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Item;

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
        return [
            'service_id' => Item::inRandomOrder()->first()->id, 
            'customer_name' => $this->faker->name,
            'appointment_date' => $this->faker->date(),
            'appointment_time' => $this->faker->time(),
            'created_at' => now(),
            'updated_at' => now(),
    
        ];
    }
}
