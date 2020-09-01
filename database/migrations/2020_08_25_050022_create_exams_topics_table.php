<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams_topics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('exams_id')->nullable();
            $table->foreign('exams_id')
                ->references('id')
                ->on('exams');
            $table->unsignedInteger('topics_id')->nullable();
            $table->foreign('topics_id')
                ->references('id')
                ->on('topics');
            $table->integer('status')->default(1); //0: Inactivo 1: Activo
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
        Schema::dropIfExists('exams_topics');
    }
}
