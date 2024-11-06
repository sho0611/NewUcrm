<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    DB::table('accounts')->insert([
        [
            'name' => 'testuser',
            'email' => 'kan@example.com',
            'password' => Hash::make('password'),
            'staff_id' => 3 
        ]
    ]);
}

}
