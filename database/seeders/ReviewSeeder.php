<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments = [
            '素晴らしい商品でした！',
            'とても満足しています。',
            '再度購入したいです。',
            '品質が良く、値段も手ごろです。',
            '期待以上の効果がありました。',
            '友人にも勧めたいです。',
            '配送が早くて助かりました。',
            'またリピートします。',
            '使いやすくて便利です。',
            'お値段以上の価値がありました。',
        ];


        $customerCount = 150;
        $itemCount = 3;

        for ($i = 0; $i < 100; $i++) { 
            DB::table('reviews')->insert([
                'customer_id' => rand(1, $customerCount), 
                'item_id' => rand(1, $itemCount),        
                'rating' => rand(1, 5),                   
                'comment' => $comments[array_rand($comments)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
