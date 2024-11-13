<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Item;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call([
            AccountSeeder::class,
            ItemSeeder::class,
            StaffSeeder::class,
        ]);

        \App\Models\Customer::factory(150)->create();
        \App\Models\Appointment::factory(500)->create();
        \App\Models\LoginHistory::factory(30)->create();

        $items = Item::all();
        Purchase::factory(1000)->create()
        ->each(function (Purchase $purchase) use ($items) {
            $purchase->items()->attach(
                $items->random(rand(1, 10))->pluck('item_id')->toArray(),
                ['quantity' => rand(1, 5)] 
            );
        });
    }
}
