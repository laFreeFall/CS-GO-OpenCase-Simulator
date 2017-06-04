<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouletteBets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roulette_bets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('roll_id')->unsigned()->index();
            $table->integer('user_id')->unsigned()->index();
            $table->integer('roulette_color_id')->unsigned()->index();
            $table->integer('value');
            $table->integer('profit');
            $table->timestamps();

            $table->foreign('roll_id')->references('id')->on('roulette_logs')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('roulette_color_id')->references('id')->on('roulette_colors')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roulette_bets');
    }
}
