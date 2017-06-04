<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsConditionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('short_title');
            $table->float('drop_rate');
        });

        DB::table('items_conditions')->insert(
            [
                ['title' => 'Factory New', 'short_title' => 'FN', 'drop_rate' => '0.2'],
                ['title' => 'Minimal Wear', 'short_title' => 'MW', 'drop_rate' => '0.2'],
                ['title' => 'Field-Tested', 'short_title' => 'FT', 'drop_rate' => '0.2'],
                ['title' => 'Well-Worn', 'short_title' => 'WW', 'drop_rate' => '0.2'],
                ['title' => 'Battle-Scarred', 'short_title' => 'BS', 'drop_rate' => '0.2'],
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
        Schema::dropIfExists('items_conditions');
    }
}
