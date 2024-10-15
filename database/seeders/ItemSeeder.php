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
            ['name' => 'カット', 'memo' => 'カットの詳細', 'price' => 6000],
            ['name' => 'カラー', 'memo' => 'カラーの詳細', 'price' => 8000],
            ['name' => 'パーマ(カット込)', 'memo' => 'パーマ、カットの詳細', 'price' => 13000],
            ['name' => 'トリートメント', 'memo' => '髪のダメージを補修し、健康的な状態に整えるトリートメント', 'price' => 5000],
            ['name' => '縮毛矯正', 'memo' => 'ストレートヘアにするための縮毛矯正', 'price' => 15000],
            ['name' => 'ヘッドスパ', 'memo' => '頭皮の血行を促進し、リラクゼーション効果のあるヘッドスパ', 'price' => 7000],
            ['name' => 'メンズカット', 'memo' => '男性向けのヘアカット', 'price' => 5000],
            ['name' => 'キッズカット', 'memo' => 'お子様向けのカット', 'price' => 4000],
            ['name' => 'アップスタイル', 'memo' => '結婚式やパーティー用のアップスタイル', 'price' => 10000],
            ['name' => 'シャンプーブロー', 'memo' => 'シャンプーとブローのセット', 'price' => 3000],
        ]);
        
    }
}
