<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printers', function (Blueprint $table) {
            $table->id();                                           //ID
            $table->foreignId('brand_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');                              //品牌ID
            $table->string('model')->unique();                      //型号
            $table->foreignId('manufactor_id')
                ->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');                              //厂商ID
            $table->enum('type', ['mono', 'color'])
                ->nullable();                                       //彩色类型
            $table->date('release_date')->nullable();               //发售日期
            $table->smallInteger('onsale')->nullable();             //是否在售
            $table->smallInteger('network')->nullable();            //网络打印
            $table->enum('duplex', ['single', 'manual', 'duplex'])
                ->nullable();                                       //双面
            $table->string('pagesize')->nullable();                 //最大处理幅面
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
        Schema::dropIfExists('printers');
    }
}
