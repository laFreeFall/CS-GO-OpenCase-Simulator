<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
        });

        DB::table('items_types')->insert(
            [
                ['title' => 'Pistols'],
                ['title' => 'Rifles'],
                ['title' => 'SMGs'],
                ['title' => 'Heavy'],
                ['title' => 'Knives'],
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
        Schema::dropIfExists('items_types');
    }
}
