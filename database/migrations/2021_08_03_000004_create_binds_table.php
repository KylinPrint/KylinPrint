<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('binds', function (Blueprint $table) {
            
            $table->foreignId('printers_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('solutions_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->smallInteger('v4_arm')->nullable();
            $table->smallInteger('v4_amd')->nullable();
            $table->smallInteger('v4_mips')->nullable();
            $table->smallInteger('v7_arm')->nullable();
            $table->smallInteger('v7_amd')->nullable();
            $table->smallInteger('v7_mips')->nullable();
            $table->smallInteger('v10_arm')->nullable();
            $table->smallInteger('v10_amd')->nullable();
            $table->smallInteger('v10_mips')->nullable();
            $table->smallInteger('v10sp1_arm')->nullable();
            $table->smallInteger('v10sp1_amd')->nullable();
            $table->smallInteger('v10sp1_mips')->nullable();
            $table->smallInteger('v10sp1_loongarch')->nullable();
            $table->string('adapter')->unique();
            $table->smallInteger('checked')->default(0);
            $table->primary(['printers_id', 'solutions_id']);
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
        Schema::dropIfExists('binds');
    }
}
