<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsNamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_names', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_type_id')->unsigned()->index();
            $table->string('title');

            $table->foreign('item_type_id')->references('id')->on('items_types')->onDelete('cascade');
        });

        DB::table('items_names')->insert(
            [
                ['item_type_id' => '1', 'title' => 'CZ75-Auto'],
                ['item_type_id' => '1', 'title' => 'Desert Eagle'],
                ['item_type_id' => '1', 'title' => 'Dual Berettas'],
                ['item_type_id' => '1', 'title' => 'Five-Seven'],
                ['item_type_id' => '1', 'title' => 'Glock-18'],
                ['item_type_id' => '1', 'title' => 'P2000'],
                ['item_type_id' => '1', 'title' => 'P250'],
                ['item_type_id' => '1', 'title' => 'R8 Revolver'],
                ['item_type_id' => '1', 'title' => 'Tec-9'],
                ['item_type_id' => '1', 'title' => 'USP-S'],
                ['item_type_id' => '2', 'title' => 'AK-47'],
                ['item_type_id' => '2', 'title' => 'AUG'],
                ['item_type_id' => '2', 'title' => 'AWP'],
                ['item_type_id' => '2', 'title' => 'Famas'],
                ['item_type_id' => '2', 'title' => 'G3SG1'],
                ['item_type_id' => '2', 'title' => 'Galil AR'],
                ['item_type_id' => '2', 'title' => 'M4A1-S'],
                ['item_type_id' => '2', 'title' => 'M4A4'],
                ['item_type_id' => '2', 'title' => 'SCAR-20'],
                ['item_type_id' => '2', 'title' => 'SG 553'],
                ['item_type_id' => '2', 'title' => 'SSG 08'],
                ['item_type_id' => '3', 'title' => 'MAC-10'],
                ['item_type_id' => '3', 'title' => 'MP7'],
                ['item_type_id' => '3', 'title' => 'MP9'],
                ['item_type_id' => '3', 'title' => 'PP-Bizon'],
                ['item_type_id' => '3', 'title' => 'P90'],
                ['item_type_id' => '3', 'title' => 'UMP-45'],
                ['item_type_id' => '4', 'title' => 'MAG-7'],
                ['item_type_id' => '4', 'title' => 'Nova'],
                ['item_type_id' => '4', 'title' => 'Sawed-Off'],
                ['item_type_id' => '4', 'title' => 'XM1014'],
                ['item_type_id' => '4', 'title' => 'M249'],
                ['item_type_id' => '4', 'title' => 'Negev'],
                ['item_type_id' => '5', 'title' => 'Bayonet'],
                ['item_type_id' => '5', 'title' => 'Bowie Knife'],
                ['item_type_id' => '5', 'title' => 'Butterfly Knife'],
                ['item_type_id' => '5', 'title' => 'Falchion Knife'],
                ['item_type_id' => '5', 'title' => 'Flip Knife'],
                ['item_type_id' => '5', 'title' => 'Gut Knife'],
                ['item_type_id' => '5', 'title' => 'Huntsman Knife'],
                ['item_type_id' => '5', 'title' => 'Karambit'],
                ['item_type_id' => '5', 'title' => 'M9 Bayonet'],
                ['item_type_id' => '5', 'title' => 'Shadow Daggers'],
                ['item_type_id' => '5', 'title' => 'Sport Gloves'],
                ['item_type_id' => '5', 'title' => 'Specialist Gloves'],
                ['item_type_id' => '5', 'title' => 'Moto Gloves'],
                ['item_type_id' => '5', 'title' => 'Hand Wraps'],
                ['item_type_id' => '5', 'title' => 'Driver Gloves'],
                ['item_type_id' => '5', 'title' => 'Bloodhound Gloves'],
//                ['item_type_id' => '5', 'title' => 'Rare Special Item'],

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
        Schema::dropIfExists('items_names');
    }
}
