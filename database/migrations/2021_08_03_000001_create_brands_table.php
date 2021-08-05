<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();                           //品牌ID
            $table->string('name')->unique();       //品牌中文名
            $table->string('name_en')->unique();    //品牌英文名
            $table->foreignId('manufactor_id')->nullable()
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');              //厂商ID
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
        Schema::dropIfExists('brands');
    }
}
