<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRouletteColorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roulette_colors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->integer('diapason_begin');
            $table->integer('diapason_end');
            $table->integer('multiplier');
        });

        DB::table('roulette_colors')->insert(
            [
                ['title' => 'Red', 'color' => '#b04a43', 'diapason_begin' => '1', 'diapason_end' => '7', 'multiplier' => '2'],
                ['title' => 'Black', 'color' => '#444', 'diapason_begin' => '8', 'diapason_end' => '14', 'multiplier' => '2'],
                ['title' => 'Green', 'color' => '#6fb26b', 'diapason_begin' => '0', 'diapason_end' => '0', 'multiplier' => '14'],
            ]
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roulette_colors');
    }
}
