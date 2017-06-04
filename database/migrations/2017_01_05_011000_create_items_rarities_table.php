<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsRaritiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_rarities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('color');
            $table->double('drop_rate');
            $table->double('drop_sum_rate');
        });

        DB::table('items_rarities')->insert(
            [
                ['title' => 'Consumer', 'color' => 'rgb(176, 195, 217)', 'drop_rate' => '79.539', 'drop_sum_rate' => '79.539'],
                ['title' => 'Industrial', 'color' =>'rgb(75, 105, 255)', 'drop_rate' => '79.539', 'drop_sum_rate' => '79.539'],
                ['title' => 'Mil-Spec', 'color' =>'rgb(94, 152, 217)', 'drop_rate' => '79.539', 'drop_sum_rate' => '79.539'],
                ['title' => 'Restricted', 'color' =>'rgb(136, 71, 255)', 'drop_rate' => '16.424', 'drop_sum_rate' => '95.963'],
                ['title' => 'Classified', 'color' =>'rgb(211, 44, 230)', 'drop_rate' => '3.257', 'drop_sum_rate' => '99.220'],
                ['title' => 'Covert', 'color' =>'rgb(235, 75, 75)', 'drop_rate' => '0.553', 'drop_sum_rate' => '99.773'],
                ['title' => 'Rare', 'color' =>'rgb(255, 215, 0)', 'drop_rate' => '0.228', 'drop_sum_rate' => '100.000'],
            ]
        );

//        DB::table('items_rarities')->insert(
//            [
//                ['title' => 'Consumer', 'color' => 'rgb(176, 195, 217)', 'drop_rate' => '79.539', 'drop_sum_rate' => '1'],
//                ['title' => 'Industrial', 'color' =>'rgb(75, 105, 255)', 'drop_rate' => '79.539', 'drop_sum_rate' => '2'],
//                ['title' => 'Mil-Spec', 'color' =>'rgb(94, 152, 217)', 'drop_rate' => '79.539', 'drop_sum_rate' => '3'],
//                ['title' => 'Restricted', 'color' =>'rgb(136, 71, 255)', 'drop_rate' => '16.424', 'drop_sum_rate' => '4'],
//                ['title' => 'Classified', 'color' =>'rgb(211, 44, 230)', 'drop_rate' => '3.257', 'drop_sum_rate' => '5'],
//                ['title' => 'Covert', 'color' =>'rgb(235, 75, 75)', 'drop_rate' => '0.553', 'drop_sum_rate' => '6'],
//                ['title' => 'Rare', 'color' =>'rgb(255, 215, 0)', 'drop_rate' => '0.228', 'drop_sum_rate' => '100.000'],
//            ]
//        );


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items_rarities');
    }
}
