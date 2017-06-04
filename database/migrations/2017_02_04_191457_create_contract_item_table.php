<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContractItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_item', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contract_id')->unsigned()->index();
            $table->integer('user_item_id')->unsigned()->index();

            $table->foreign('contract_id')->references('id')->on('contracts_logs')->onDelete('cascade');
            $table->foreign('user_item_id')->references('id')->on('items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_item');
    }
}
