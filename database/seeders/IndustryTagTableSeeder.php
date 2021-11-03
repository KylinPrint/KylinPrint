<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IndustryTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('industry_tags')->insert(
            ['id' => '1','name' => '医疗'],
            ['id' => '2','name' => '教育'],
            ['id' => '3','name' => '交通'],
            ['id' => '4','name' => '企业'],
            ['id' => '5','name' => '金融'],
            ['id' => '6','name' => '政府'],
            ['id' => '7','name' => '通信'],
            ['id' => '8','name' => '能源']
        );
    }
}
