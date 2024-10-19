<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('staff')->insert([
            [
                'name' => '田中 太郎',
                'memo' => 'スタイリスト。カットが得意です。',
            ],
            [
                'name' => '鈴木 花子',
                'memo' => 'カラーリスト。色彩感覚が抜群です。',
            ],
            [
                'name' => '佐藤 次郎',
                'memo' => 'パーマ専門。ふんわりしたスタイルを提案します。',
            ],
            [
                'name' => '高橋 美咲',
                'memo' => 'トリートメントに特化。髪のケアが得意です。',
            ],
            [
                'name' => '小林 大輔',
                'memo' => 'メンズスタイル専門。スタイリングもお任せ。',
            ],
            [
                'name' => '山田 桃子',
                'memo' => 'アシスタント。サポートが得意です。',
            ],
            [
                'name' => '中村 聡',
                'memo' => 'ヘアセットが得意。特別な日のお手伝いをします。',
            ],
            [
                'name' => '井上 絵美',
                'memo' => 'フルコースが得意。トータルビューティーを提供します。',
            ],
            [
                'name' => '松本 健太',
                'memo' => 'ショートスタイルが得意。似合わせに自信あり。',
            ],
            [
                'name' => '渡辺 美紀',
                'memo' => 'ナチュラルスタイル専門。お客様の魅力を引き出します。',
            ],
        ]);
        
    }
}
