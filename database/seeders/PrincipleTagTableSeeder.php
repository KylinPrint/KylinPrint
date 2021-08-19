<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrincipleTagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('principle_tags')->insert(
            ['id' => '1','name' => '热敏打印机'],
            ['id' => '2','name' => '喷墨打印机'],
            ['id' => '3','name' => '激光打印机'],
            ['id' => '4','name' => '针式打印机']
        );
    }
}
