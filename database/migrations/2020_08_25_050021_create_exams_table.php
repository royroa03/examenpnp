<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('grades_id')->nullable();
            $table->foreign('grades_id')
                ->references('id')
                ->on('grades');
            $table->unsignedInteger('students_id')->nullable();
            $table->foreign('students_id')
                ->references('id')
                ->on('students');
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
        Schema::dropIfExists('exams');
    }
}
