<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndustryTagBindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('industry_tag_binds', function (Blueprint $table) {
            $table->foreignId('printers_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->foreignId('industry_tags_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
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
        Schema::dropIfExists('industry_tag_binds');
    }
}
