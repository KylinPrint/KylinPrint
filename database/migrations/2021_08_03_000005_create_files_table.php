<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();                       //附件ID
            $table->bigInteger('parent_id')->default('0');
                                                //父目录id
            $table->string('title');            //行名称
            $table->string('path');             //附件路径
            $table->foreignId('solutions_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');          //所属解决方案
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
        Schema::dropIfExists('files');
    }
}
