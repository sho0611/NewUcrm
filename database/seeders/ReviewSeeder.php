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
            'また利用したいです',
            '品質が良く、値段も手ごろです。',
            '期待以上の効果がありました。',
            '友人にも勧めたいです。',
            '店員さんが優しかったです',
            'またリピートします。',
            '使いやすくて便利です。',
            'お値段以上の価値がありました。',
        ];

        $customerNames = [
            'ジロー',
            'ハナ',
            'アキ',
            'ケン',
            'ナオ',
            'エミ',
            'ジン',
            'ミサキ',
            'ユウタ',
            'タカ',
            'タロウ',
            'サクラ',
            'ユキ',
            'コウタ',
            'マリ',
        ];
    

        $itemCount = 20;

        for ($i = 0; $i < 100; $i++) { 
            DB::table('reviews')->insert([
                'service_id' => rand(1, $itemCount),
                'customer_name' => $customerNames[array_rand($customerNames)],     
                'rating' => rand(1, 5),                   
                'comment' => $comments[array_rand($comments)],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
