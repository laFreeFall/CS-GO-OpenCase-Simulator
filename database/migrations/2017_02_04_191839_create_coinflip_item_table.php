<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoinflipItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coinflip_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('coinflip_id')->unsigned()->index();
            $table->integer('item_id')->unsigned()->index();
            $table->boolean('user_item');

            $table->foreign('coinflip_id')->references('id')->on('coinflip_logs')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coinflip_item');
    }
}
