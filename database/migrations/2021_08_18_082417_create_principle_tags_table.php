<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class CreatePrincipleTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('principle_tags', function (Blueprint $table) {
            $table->id();               //打印机工作原理标签ID
            $table->string('name');     //打印机工作原理标签名
            $table->timestamps();
        });

        Artisan::call('db:seed', [
            '--class' => PrincipleTagTableSeeder::class
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('principle_tags');
    }
}
