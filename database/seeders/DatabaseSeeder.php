<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
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
            ItemSeeder::class
        ]);

         \App\Models\Customer::factory(1500)->create();

         $items = Item::all();

         Purchase::factory(30000)->create()
         ->each(function (Purchase $purchase) use ($items) {
             $purchase->items()->attach(
                 $items->random(rand(1, 3))->pluck('id')->toArray(),
                 ['quantity' => rand(1, 5)] 
             );
         });




        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);



    }
}
