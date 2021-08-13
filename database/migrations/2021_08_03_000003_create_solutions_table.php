<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->id();                          //解决方案ID
            $table->string('name')->unique();      //解决方案名
            $table->string('comment')->nullable(); //解决方案简介
            $table->string('source');              //解决方案来源:自研/开源/官方
            $table->text('detail');
            $table->smallInteger('amd64')->default(0);
            $table->smallInteger('arm64')->default(0);
            $table->smallInteger('mips64el')->default(0);
            $table->smallInteger('loongarch64')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solutions');
    }
}
