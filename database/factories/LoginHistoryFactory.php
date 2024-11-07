<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LoginHistory>
 */
class LoginHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
   

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('2024-11-01', '2024-11-30')->format('Y-m-d');  
    
        return [
            'staff_id' => 1,  
            'account_id' => 1,
            'login_time' => $date . ' 09:00:00',   
            'logout_time' => $date . ' 18:00:00',  
        ];
    }
}
