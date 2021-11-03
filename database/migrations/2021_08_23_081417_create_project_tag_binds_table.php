<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectTagBindsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_tag_binds', function (Blueprint $table) {
            $table->foreignId('printers_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->foreignId('project_tags_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');;
            $table->string('note');
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
        Schema::dropIfExists('project__tag__binds');
    }
}
