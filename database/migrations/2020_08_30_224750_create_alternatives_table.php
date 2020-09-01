<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlternativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternatives', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('questions_id')->nullable();
            $table->foreign('questions_id')
                ->references('id')
                ->on('questions');
            $table->string('alternative');
            $table->string('name_alternative');
            $table->integer('is_correct')->default(0);
            $table->integer('response')->default(0);
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
        Schema::dropIfExists('alternatives');
    }
}
