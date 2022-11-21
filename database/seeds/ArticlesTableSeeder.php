<?php

use Illuminate\Database\Seeder;

class ArticlesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 30; $i++) {
 
            DB::table('articles')->insert([
                'title' => 'タイトルこんにちは: '.$i,
                'body' => 'ボディこんにちは: '.$i,
                'user_id' => '1',
                'created_at' => new DateTime(),
                'updated_at' => new DateTime(),
            ]);
        }
    }
}
