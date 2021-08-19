<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreateIndustryTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_tags', function (Blueprint $table) {
            $table->id();               //应用行业ID
            $table->string('name');     //应用行业名
            $table->timestamps();       
        });

        Artisan::call('db:seed', [
            '--class' => IndustryTagTableSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('industry_tags');
    }
}
