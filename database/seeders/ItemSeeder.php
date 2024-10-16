<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            ['name' => 'カット', 'memo' => 'カットの詳細', 'price' => 6000, 'duration' => 30],
            ['name' => 'カラー', 'memo' => 'カラーの詳細', 'price' => 8000, 'duration' => 60],
            ['name' => 'パーマ', 'memo' => 'パーマの詳細', 'price' => 13000, 'duration' => 90],
            ['name' => 'トリートメント', 'memo' => 'トリートメントの詳細', 'price' => 5000, 'duration' => 45],
            ['name' => 'ヘッドスパ', 'memo' => 'ヘッドスパの詳細', 'price' => 7000, 'duration' => 60],
            ['name' => 'セット', 'memo' => 'セットの詳細', 'price' => 4000, 'duration' => 30],
            ['name' => '眉カット', 'memo' => '眉カットの詳細', 'price' => 3000, 'duration' => 15],
            ['name' => '髪質改善', 'memo' => '髪質改善の詳細', 'price' => 12000, 'duration' => 120],
            ['name' => 'カラーリタッチ', 'memo' => 'カラーリタッチの詳細', 'price' => 5000, 'duration' => 45],
            ['name' => 'ストレートパーマ', 'memo' => 'ストレートパーマの詳細', 'price' => 15000, 'duration' => 150],
        ]);
        
    }
}
